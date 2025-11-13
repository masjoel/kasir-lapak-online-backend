<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $resellerId = Auth::user()->reseller_id;
        $cari = $request->input('search') ?: Auth::user()->email;
        $resellerId = User::when($cari, function ($query, $search) {
            return $query->where('email', '=', $search);
        })->first()->reseller_id ?? Auth::user()->reseller_id;

        $title = 'Dashboard';
        $sortField = $request->input('sort_field') == 'status' ? 'booking_id' : $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_order', 'desc');
        $viewer = DB::table('userlog')->where('reseller_id', $resellerId)->distinct('ipaddr')->count();
        $referral = User::where('marketing', $resellerId)->count();
        $komisi = User::where('marketing', $resellerId)->where('booking_id', '!=', null)->count();
        $isBayar = User::where('marketing', $resellerId)->where('booking_id', '!=', null)->where('is_bayar', '1')->count();
        $potensiKomisi = $referral * 50000;
        $totalKomisi = $komisi * 50000;
        $bayarKomisi = $isBayar * 50000;
        $orders = User::where('marketing', $resellerId)
            ->orderBy($sortField, $sortDirection)
            ->paginate(10);
        if (Auth::user()->email == 'owner@tokopojok.com') {
            return view('pages.users.referral', compact('title', 'orders', 'sortDirection', 'referral', 'potensiKomisi', 'totalKomisi', 'bayarKomisi', 'viewer'));
        } else {
            $users = User::where('email', Auth::user()->email)->paginate(10);
            return view('pages.dboard', compact('title', 'users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Reseller $reseller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reseller $reseller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reseller $reseller)
    {
        //
    }
    public function bayar(Request $request)
    {
        $isBayar = User::find($request->id);
        if (!$isBayar) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }
        $isBayar->is_bayar = 1; // ubah status jadi sudah bayar
        $isBayar->save();
        return response()->json(['success' => true, 'message' => 'Status pembayaran diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reseller $reseller)
    {
        //
    }
}
