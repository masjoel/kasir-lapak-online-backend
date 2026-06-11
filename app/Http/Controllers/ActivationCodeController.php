<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use App\Models\User;
use Illuminate\Http\Request;

class ActivationCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $codes = ActivationCode::orderBy('id', 'desc')
            ->when($request->input('cari'), function ($query, $cari) {
                return $query->where('code', 'like', '%' . $cari . '%')
                    ->orWhere('type', 'like', '%' . $cari . '%')
                    ->orWhere('email', 'like', '%' . $cari . '%');
            })
            ->when($request->filled('is_used'), function ($query) use ($request) {
                return $query->where('is_used', $request->input('is_used') === 'true' ? 1 : 0);
            })
            ->paginate(48);
        $title = 'Activation Code';
        return view('pages.activation-code.index', compact('title', 'codes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = ['starter', 'basic', 'pro'];
        foreach ($types as $type) {
            for ($i = 0; $i < 10; $i++) {
                ActivationCode::create([
                    'code' => strtoupper(bin2hex(random_bytes(
                        $type === 'starter' ? 4 : ($type === 'basic' ? 5 : 6)
                    ))),
                    'type' => $type,
                    'is_used' => 0,
                ]);
            }
        }
        return redirect()->route('activation-code.index')->with('success', 'Activation codes successfully generated');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivationCode $activationCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivationCode $activationCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivationCode $activationCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivationCode $activationCode)
    {
        //
    }

    public function activateWithCode(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|exists:activation_codes,code',
            'type' => 'required|string|in:starter,basic,pro',
            'email' => 'required|email',
        ]);
        $activationCode = ActivationCode::where('code', $data['code'])
            ->where('type', $data['type'])
            ->first();

        if (! $activationCode) {
            return redirect()->route('activation-code.index')->with('error', 'Activation code tidak ditemukan.');
        }
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return redirect()->route('activation-code.index')->with('error', 'Email tidak ditemukan atau tidak valid.');
        }
        if (!empty($user->booking_id)) {
            return redirect()->route('activation-code.index')->with('error', 'Email sudah digunakan atau tidak valid.');
        }

        $user->update([
            'phone' => $data['code'],
            'booking_id' => $data['code'],
            'device_id' => '0',
            'email_verified_at' => now(),
            'is_type' => $data['type'] === 'starter' ? 1 : ($data['type'] === 'basic' ? 2 : 3),
        ]);

        $activationCode->update([
            'is_used' => 1,
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return redirect()->route('activation-code.index')->with('success', 'Email berhasil disimpan ke activation code.');
    }
}
