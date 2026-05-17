<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\PembelianBahan;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        
        // Transaksi Hari Ini
        $transaksiHariIni = TransaksiPenjualan::whereDate('waktu_transaksi', $today)->count();
        
        // Pendapatan Hari Ini
        $pendapatanHariIni = TransaksiPenjualan::whereDate('waktu_transaksi', $today)->sum('total_harga');

        // Menu Tersedia & Habis
        $semuaProduk = Produk::all();
        $totalMenu = $semuaProduk->count();
        $menuTersedia = $semuaProduk->where('status', 'tersedia')->count();
        $menuHabis = $semuaProduk->where('status', 'habis')->count();

        // Grafik Penjualan (7 hari terakhir)
        $grafikPenjualan = [];
        $grafikLabel = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            // Label dalam bahasa indonesia misal Senin, Selasa
            Carbon::setLocale('id');
            $grafikLabel[] = $date->translatedFormat('D');
            $grafikPenjualan[] = TransaksiPenjualan::whereDate('waktu_transaksi', $date)->sum('total_harga');
        }

        // Item Terjual Hari Ini
        $itemTerjualHariIni = DetailTransaksi::whereHas('transaksi', function($q) use ($today) {
            $q->whereDate('waktu_transaksi', $today);
        })->sum('jumlah');

        // Menu Terlaris
        $menuTerlaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $statusMenu = $semuaProduk;

        return view('dashboard', compact(
            'transaksiHariIni',
            'pendapatanHariIni',
            'totalMenu',
            'menuTersedia',
            'menuHabis',
            'grafikLabel',
            'grafikPenjualan',
            'itemTerjualHariIni',
            'menuTerlaris',
            'statusMenu'
        ));
    }
}
