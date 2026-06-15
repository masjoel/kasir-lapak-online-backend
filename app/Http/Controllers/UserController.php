<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\AutoNumberHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserReq;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->email == 'owner@tokopojok.com') {
            $users = User::orderBy('id', 'desc')
                ->where('email', '!=', 'owner@tokopojok.com')
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where(function ($q) use ($name) {
                        $q->where('name', 'like', '%' . $name . '%')
                          ->orWhere('email', 'like', '%' . $name . '%')
                          ->orWhere('phone', 'like', '%' . $name . '%')
                          ->orWhere('roles', 'like', '%' . $name . '%');
                    });
                })
                ->when($request->input('is_type') !== null && $request->input('is_type') !== '', function ($query) use ($request) {
                    return $query->where('is_type', $request->input('is_type'));
                })
                ->paginate(10);
        } else {
            $users = User::where('email', Auth::user()->email)->paginate(10);
        }
        return view('pages.users.index', compact('users'));
    }

    public function export(Request $request)
    {
        $typeLabels = [0 => 'Trial', 1 => 'Starter', 2 => 'Basic', 3 => 'Pro'];

        $query = User::orderBy('id', 'desc')
            ->where('email', '!=', 'owner@tokopojok.com')
            ->when($request->input('name'), function ($q, $name) {
                return $q->where(function ($q2) use ($name) {
                    $q2->where('name', 'like', '%' . $name . '%')
                       ->orWhere('email', 'like', '%' . $name . '%')
                       ->orWhere('telpon', 'like', '%' . $name . '%')
                       ->orWhere('roles', 'like', '%' . $name . '%');
                });
            })
            ->when($request->input('is_type') !== null && $request->input('is_type') !== '', function ($q) use ($request) {
                return $q->where('is_type', $request->input('is_type'));
            });

        $users = $query->get(['id', 'name', 'email', 'roles', 'telpon', 'is_type', 'marketing', 'phone', 'booking_id', 'device_id', 'created_at']);

        $filename = 'users_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($users, $typeLabels) {
            $handle = fopen('php://output', 'w');

            // BOM untuk Excel agar UTF-8 terbaca dengan benar
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($handle, ['ID', 'Name', 'Email', 'Roles', 'Telpon', 'Type', 'Reseller', 'Subscription', 'Login Status', 'Created At']);

            foreach ($users as $user) {
                $type        = $typeLabels[$user->is_type] ?? '-';
                $subscription = ($user->phone == $user->booking_id) ? 'Lifetime' : 'Trial';
                $loginStatus  = ($user->device_id != 0) ? 'Login' : 'Logout';

                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucwords($user->roles),
                    $user->telpon,
                    $type,
                    $user->marketing,
                    $subscription,
                    $loginStatus,
                    $user->created_at,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function create()
    {
        return view('pages.users.create');
    }

    public function store(StoreUserReq $request)
    {
        $data = $request->all();
        if ($request->roles !== 'reseller') {
            $data['reseller_id'] = null;
        }
        $data['password'] = Hash::make($request->password);
        User::create($data);
        return redirect()->route('user.index')->with('success', 'User successfully created');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if ($request->password !== null) {
            $data['password'] = Hash::make($request->password);
        }
        if ($request->roles == 'reseller' && $user->reseller_id == null) {
            $idReseller = AutoNumberHelper::initGenerateNumber('NOMOR');
            $data['reseller_id'] = $idReseller;
        }
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'User successfully updated');
    }
    public function upgradeStarter($confirmation_code)
    {
        $user = User::where('phone', $confirmation_code)->update(['is_type' => '1']);
        return redirect()->route('home')->with('success', 'Akun berhasil diupgrade ke paket Starter');
    }
    public function upgradeBasic($confirmation_code)
    {
        $user = User::where('phone', $confirmation_code)->update(['is_type' => '2']);
        return redirect()->route('home')->with('success', 'Akun berhasil diupgrade ke paket Basic');
    }
    public function upgradePro($confirmation_code)
    {
        $user = User::where('phone', $confirmation_code)->update(['is_type' => '3']);
        return redirect()->route('home')->with('success', 'Akun berhasil diupgrade ke paket Pro');
    }
    public function starter($confirmation_code)
    {
        if (! $this->activateWithCode('starter', $confirmation_code, 1)) {
            return redirect()->route('home')->with('error', 'Kode starter tidak tersedia atau pengguna tidak ditemukan.');
        }

        return redirect()->route('home')->with('success', 'Akun Anda sudah aktif, silahkan login di aplikasi Kasir');
    }

    public function basic($confirmation_code)
    {
        if (! $this->activateWithCode('basic', $confirmation_code, 2)) {
            return redirect()->route('register.success')->with('error', 'Kode basic tidak tersedia atau pengguna tidak ditemukan.');
        }

        return redirect()->route('register.success')->with('success', 'Akun Anda sudah aktif, silahkan login di aplikasi Kasir');
    }

    public function konfirmasi($confirmation_code)
    {
        if (! $this->activateWithCode('pro', $confirmation_code, 3)) {
            return redirect()->route('register.success')->with('error', 'Kode pro tidak tersedia atau pengguna tidak ditemukan.');
        }

        return redirect()->route('register.success')->with('success', 'Akun Anda sudah aktif, silahkan login di aplikasi Kasir');
    }

    private function activateWithCode(string $type, string $confirmation_code, int $packageLevel): bool
    {
        $activationCode = ActivationCode::where('code', $confirmation_code)
            ->where('type', $type)
            ->where('is_used', false)
            ->first();

        $phone = request('phone');
        if (! $activationCode || ! $phone) {
            return false;
        }

        $user = User::where('phone', $phone)->first();
        if (! $user || $user->booking_id !== null) {
            return false;
        }

        $user->update([
            'phone' => $confirmation_code,
            'booking_id' => $confirmation_code,
            'device_id' => '0',
            'email_verified_at' => now(),
            'is_type' => $packageLevel,
        ]);

        $activationCode->update([
            'is_used' => 1,
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return true;
    }

    public function registerSuccess()
    {
        $title = 'Konfirmasi Sukses';
        return view('pages.users.register-success', compact('title'));
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        $user->delete();
        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Data'
        ]);
    }
}
