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
        $dateStr = $request->get('date', date('d/m/Y'));
        
        try {
            $selectedDate = Carbon::createFromFormat('d/m/Y', $dateStr);
        } catch (\Exception $e) {
            $selectedDate = Carbon::today();
        }

        // Transaksi sesuai tanggal
        $transaksiHariIni = TransaksiPenjualan::whereDate('waktu_transaksi', $selectedDate)->count();
        
        // Pendapatan sesuai tanggal
        $pendapatanHariIni = TransaksiPenjualan::whereDate('waktu_transaksi', $selectedDate)->sum('total_harga');

        // Menu Tersedia & Habis
        $semuaProduk = Produk::all();
        $totalMenu = $semuaProduk->count();
        $menuTersedia = $semuaProduk->where('status', 'tersedia')->count();
        $menuHabis = $semuaProduk->where('status', 'habis')->count();

        // Grafik Penjualan (7 hari terakhir dari selected date)
        $grafikPenjualan = [];
        $grafikLabel = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $selectedDate->copy()->subDays($i);
            // Label dalam bahasa indonesia misal Senin, Selasa
            Carbon::setLocale('id');
            $grafikLabel[] = $date->translatedFormat('D');
            $grafikPenjualan[] = TransaksiPenjualan::whereDate('waktu_transaksi', $date)->sum('total_harga');
        }

        // Item Terjual sesuai tanggal
        $itemTerjualHariIni = DetailTransaksi::whereHas('transaksi', function($q) use ($selectedDate) {
            $q->whereDate('waktu_transaksi', $selectedDate);
        })->sum('jumlah');

        // Menu Terlaris sesuai tanggal
        $menuTerlaris = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('transaksi_penjualan', 'detail_transaksi.id_transaksi', '=', 'transaksi_penjualan.id_transaksi')
            ->whereDate('transaksi_penjualan.waktu_transaksi', $selectedDate)
            ->select('produk.nama_produk', DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $statusMenu = $semuaProduk;

        return view('dashboard', compact(
            'dateStr',
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
