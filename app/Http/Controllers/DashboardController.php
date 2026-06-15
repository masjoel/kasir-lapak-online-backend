<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $starterCode = null;
        $basicCode = null;
        $proCode = null;

        if (Auth::user()->email == 'owner@tokopojok.com') {
            $starterCode = ActivationCode::where('type', 'starter')->where('is_used', false)->first();
            $basicCode = ActivationCode::where('type', 'basic')->where('is_used', false)->first();
            $proCode = ActivationCode::where('type', 'pro')->where('is_used', false)->first();

            $users = User::with('activationCode')
                ->orderBy('id', 'desc')
                ->where('email', '!=', 'owner@tokopojok.com')
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where(function ($query) use ($name) {
                        $query->where('name', 'like', '%' . $name . '%')
                            ->orWhere('email', 'like', '%' . $name . '%')
                            ->orWhere('phone', 'like', '%' . $name . '%');
                    });
                })
                ->when($request->input('is_type') !== null && $request->input('is_type') !== '', function ($query) use ($request) {
                    return $query->where('is_type', $request->input('is_type'));
                })
                ->paginate(10);
        } else {
            $users = User::where('email', Auth::user()->email)->paginate(10);
        }
        $title = 'Dashboard';
        return view('pages.dboard', compact('title', 'users', 'starterCode', 'basicCode', 'proCode'));
    }

    public function export(Request $request)
    {
        $typeLabels = [0 => 'Trial', 1 => 'Starter', 2 => 'Basic', 3 => 'Pro'];

        $users = User::orderBy('id', 'desc')
            ->where('email', '!=', 'owner@tokopojok.com')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where(function ($q) use ($name) {
                    $q->where('name', 'like', '%' . $name . '%')
                        ->orWhere('email', 'like', '%' . $name . '%')
                        ->orWhere('telpon', 'like', '%' . $name . '%');
                });
            })
            ->when($request->input('is_type') !== null && $request->input('is_type') !== '', function ($query) use ($request) {
                return $query->where('is_type', $request->input('is_type'));
            })
            ->get(['id', 'name', 'email', 'roles', 'telpon', 'is_type', 'marketing', 'phone', 'booking_id', 'device_id', 'created_at']);

        $filename = 'dashboard_users_' . now()->format('Ymd_His') . '.csv';

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

            fputcsv($handle, ['ID', 'Name', 'Email', 'Roles', 'Telpon', 'Type', 'Marketing', 'Subscription', 'Login Status', 'Created At']);

            foreach ($users as $user) {
                $type         = $typeLabels[$user->is_type] ?? '-';
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
}
