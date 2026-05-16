<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\SesiKasir;
use Illuminate\Support\Facades\Auth;

class TransaksiKasirController extends Controller
{
    public function index(Request $request)
    {
        // Validasi apakah sesi kasir sudah dibuka
        $sesiAktif = SesiKasir::whereNull('waktu_tutup')->where('id_user', Auth::id())->first();

        if (!$sesiAktif) {
            return redirect()->route('sesi-kasir')->with('error', 'Silakan buka sesi kasir terlebih dahulu sebelum mengakses halaman transaksi.');
        }

        $kategoris = Kategori::all();
        
        $query = Produk::with('kategori');
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }
        $produk = $query->get();

        return view('transaksi', compact('kategoris', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_harga' => 'required|numeric',
            'bayar' => 'required|numeric',
            'kembalian' => 'required|numeric',
            'items' => 'required|string', // Items dikirim sebagai JSON string atau array
        ]);

        $sesiAktif = SesiKasir::whereNull('waktu_tutup')->where('id_user', Auth::id())->first();

        if (!$sesiAktif) {
            return redirect()->route('sesi-kasir')->with('error', 'Sesi kasir belum dibuka.');
        }

        // Decode items jika dikirim dalam bentuk JSON string dari hidden input
        $items = json_decode($request->items, true);

        if (!$items || count($items) == 0) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        if ($request->bayar < $request->total_harga) {
            return redirect()->back()->with('error', 'Uang bayar kurang dari total harga.');
        }

        // 1. Simpan Transaksi Penjualan
        $transaksi = \App\Models\TransaksiPenjualan::create([
            'id_sesi' => $sesiAktif->id_sesi,
            'waktu_transaksi' => now(),
            'total_harga' => $request->total_harga,
            'bayar' => $request->bayar,
            'kembalian' => $request->kembalian,
            'tipe_pesanan' => $request->tipe_pesanan,
            'nama_pembeli' => $request->nama_pembeli,
            'nomor_meja' => $request->nomor_meja,
        ]);

        // 2. Simpan Detail Transaksi
        foreach ($items as $item) {
            \App\Models\DetailTransaksi::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'id_produk' => $item['id_produk'],
                'jumlah' => $item['qty'],
                'subtotal' => $item['harga'] * $item['qty'],
            ]);
        }

        return redirect()->route('transaksi')->with([
            'success' => 'Transaksi berhasil disimpan!',
            'cetak_struk' => $transaksi->id_transaksi
        ]);
    }

    public function struk($id_transaksi)
    {
        $transaksi = \App\Models\TransaksiPenjualan::with(['detailTransaksi.produk', 'sesiKasir.user'])->findOrFail($id_transaksi);
        return view('transaksi-struk', compact('transaksi'));
    }

    public function detail($id_transaksi)
    {
        $transaksi = \App\Models\TransaksiPenjualan::with(['detailTransaksi.produk', 'sesiKasir.user'])->findOrFail($id_transaksi);
        return view('transaksi-detail', compact('transaksi'));
    }
}
