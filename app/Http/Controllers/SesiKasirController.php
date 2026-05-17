<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiKasir;
use Illuminate\Support\Facades\Auth;

class SesiKasirController extends Controller
{
    public function index()
    {
        $sesiAktif = SesiKasir::with('transaksiPenjualan')
                        ->whereNull('waktu_tutup')
                        ->latest('waktu_buka')
                        ->first();

        $riwayatSesi = SesiKasir::with(['user', 'transaksiPenjualan'])
                        ->orderBy('waktu_buka', 'desc')
                        ->get();

        return view('sesi-kasir', compact('sesiAktif', 'riwayatSesi'));
    }

    public function bukaKasir(Request $request)
    {
        $sesiAktif = SesiKasir::whereNull('waktu_tutup')->first();

        if (!$sesiAktif) {
            SesiKasir::create([
                'id_user'    => Auth::id(),
                'waktu_buka' => now(),
            ]);
        }

        return redirect()->route('sesi-kasir')->with('success', 'Sesi Kasir Berhasil Dibuka');
    }

    public function tutupKasir(Request $request)
    {
        $sesiAktif = SesiKasir::whereNull('waktu_tutup')->latest('waktu_buka')->first();

        if ($sesiAktif) {
            $sesiAktif->update([
                'waktu_tutup' => now(),
            ]);
        }

        return redirect()->route('sesi-kasir')->with('success', 'Sesi Kasir Berhasil Ditutup');
    }

    public function bukuKasir()
    {
        $riwayatSesi = SesiKasir::with(['user', 'transaksiPenjualan'])
                        ->orderBy('waktu_buka', 'desc')
                        ->get();

        return view('buku-kasir', compact('riwayatSesi'));
    }

    public function detailBukuKasir($id_sesi)
    {
        $sesi = SesiKasir::with(['user', 'transaksiPenjualan'])->findOrFail($id_sesi);

        return view('buku-kasir-detail', compact('sesi'));
    }
}