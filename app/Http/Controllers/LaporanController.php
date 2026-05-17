<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPenjualan;
use App\Models\BahanBaku;
use App\Models\PembelianBahan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'mingguan'); // harian, mingguan, bulanan
        $dateStr = $request->get('date', date('d/m/Y'));
        
        try {
            $currentDate = Carbon::createFromFormat('d/m/Y', $dateStr);
        } catch (\Exception $e) {
            $currentDate = Carbon::today();
        }

        if ($filter == 'harian') {
            $startDate = $currentDate->copy()->startOfDay();
            $endDate = $currentDate->copy()->endOfDay();
        } elseif ($filter == 'mingguan') {
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate = $currentDate->copy()->endOfWeek();
        } else { // bulanan
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
        }

        // Laba Rugi & Rekapitulasi Card
        $totalPenjualan = TransaksiPenjualan::whereBetween('waktu_transaksi', [$startDate, $endDate])->sum('total_harga');
        $totalHpp = BahanBaku::whereBetween('tgl_pembelian', [$startDate, $endDate])->sum('total_pengeluaran');
        
        $labaKotor = $totalPenjualan - $totalHpp;
        $marginKotor = $totalPenjualan > 0 ? round(($labaKotor / $totalPenjualan) * 100) : 0;

        // Mendapatkan rekapitulasi data berdasarkan tanggal
        $rekapitulasi = [];
        $tempDate = $startDate->copy();
        
        while ($tempDate <= $endDate) {
            $tgl = $tempDate->toDateString();
            
            $penjualan = TransaksiPenjualan::whereDate('waktu_transaksi', $tgl)->sum('total_harga');
            $hpp = BahanBaku::whereDate('tgl_pembelian', $tgl)->sum('total_pengeluaran');
            $laba = $penjualan - $hpp;
            $margin = $penjualan > 0 ? round(($laba / $penjualan) * 100, 1) : 0;
            
            if ($penjualan > 0 || $hpp > 0) {
                $rekapitulasi[] = (object)[
                    'tanggal' => $tempDate->format('d/m/Y'),
                    'pendapatan' => $penjualan,
                    'hpp' => $hpp,
                    'laba_kotor' => $laba,
                    'margin' => $margin
                ];
            }
            
            $tempDate->addDay();
        }

        return view('laporan', compact(
            'filter', 
            'dateStr', 
            'totalPenjualan', 
            'totalHpp', 
            'labaKotor', 
            'marginKotor',
            'rekapitulasi'
        ));
    }

    public function export(Request $request)
    {
        $filter = $request->get('filter', 'mingguan');
        $dateStr = $request->get('date', date('d/m/Y'));
        
        try {
            $currentDate = Carbon::createFromFormat('d/m/Y', $dateStr);
        } catch (\Exception $e) {
            $currentDate = Carbon::today();
        }

        if ($filter == 'harian') {
            $startDate = $currentDate->copy()->startOfDay();
            $endDate = $currentDate->copy()->endOfDay();
        } elseif ($filter == 'mingguan') {
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate = $currentDate->copy()->endOfWeek();
        } else {
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
        }

        $fileName = 'Laporan_Keuangan_' . ucfirst($filter) . '_' . date('YmdHis') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Tanggal', 'Pendapatan (Total Penjualan)', 'HPP (Total Pembelian Bahan)', 'Laba Kotor', 'Margin (%)'];

        $callback = function() use($startDate, $endDate, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $tempDate = $startDate->copy();
            while ($tempDate <= $endDate) {
                $tgl = $tempDate->toDateString();
                $penjualan = TransaksiPenjualan::whereDate('waktu_transaksi', $tgl)->sum('total_harga');
                $hpp = BahanBaku::whereDate('tgl_pembelian', $tgl)->sum('total_pengeluaran');
                $laba = $penjualan - $hpp;
                $margin = $penjualan > 0 ? round(($laba / $penjualan) * 100, 1) : 0;
                
                if ($penjualan > 0 || $hpp > 0) {
                    fputcsv($file, [
                        $tempDate->format('d/m/Y'),
                        $penjualan,
                        $hpp,
                        $laba,
                        $margin . '%'
                    ]);
                }
                
                $tempDate->addDay();
            }

            // Total
            $totalPenjualan = TransaksiPenjualan::whereBetween('waktu_transaksi', [$startDate, $endDate])->sum('total_harga');
            $totalHpp = BahanBaku::whereBetween('tgl_pembelian', [$startDate, $endDate])->sum('total_pengeluaran');
            $labaKotor = $totalPenjualan - $totalHpp;
            $marginKotor = $totalPenjualan > 0 ? round(($labaKotor / $totalPenjualan) * 100, 1) : 0;

            fputcsv($file, ['', '', '', '', '']); // Empty row
            fputcsv($file, ['TOTAL', $totalPenjualan, $totalHpp, $labaKotor, $marginKotor . '%']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
