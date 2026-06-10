<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
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
}
