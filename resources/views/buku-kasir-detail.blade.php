@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px] space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-1">Detail Sesi Kasir</h2>
            <p class="text-sm font-semibold text-gray-500">Sesi Kasir: SESI-{{ $sesi->id_sesi }} | Kasir: {{ $sesi->user->username ?? 'Unknown' }}</p>
        </div>
        <div class="w-full sm:w-auto flex justify-end">
            <a href="{{ route('buku-kasir') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-[#c5cb9f] text-black font-bold rounded-xl border border-gray-400/60 text-sm hover:bg-[#b8be92] transition shadow-sm">
                Kembali
            </a>
        </div>
    </div>
    
    <div class="border-t border-black/20 mb-6"></div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex flex-col justify-between shadow-sm h-44">
            <h4 class="font-bold text-sm text-gray-500">Total Transaksi</h4>
            <p class="text-4xl font-bold text-gray-950 my-2">{{ $sesi->transaksiPenjualan->count() }}</p>
            <span class="bg-[#788e5e] text-white text-xs font-bold text-center px-4 py-1.5 rounded-xl w-full block shadow-sm">Selesai</span>
        </div>
        
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex flex-col justify-between shadow-sm h-44">
            <h4 class="font-bold text-sm text-gray-500">Total Pendapatan</h4>
            <p class="text-3xl font-bold text-gray-950 my-2">Rp {{ number_format($sesi->transaksiPenjualan->sum('total_harga'), 0, ',', '.') }}</p>
            <span class="bg-[#4e825a] text-white text-xs font-bold text-center px-4 py-1.5 rounded-xl w-full block shadow-sm">Rupiah (IDR)</span>
        </div>
        
        <div class="bg-white rounded-2xl border border-gray-200 p-5 flex flex-col justify-between shadow-sm h-44 sm:col-span-2 lg:col-span-1">
            <h4 class="font-bold text-sm text-gray-500">Waktu Sesi</h4>
            <div class="flex flex-col gap-1 my-1">
                <div class="text-xs md:text-sm font-semibold text-gray-800"><span class="text-gray-400 font-medium">Buka:</span> {{ \Carbon\Carbon::parse($sesi->waktu_buka)->format('d M Y H:i') }}</div>
                <div class="text-xs md:text-sm font-semibold text-gray-800"><span class="text-gray-400 font-medium">Tutup:</span> {{ $sesi->waktu_tutup ? \Carbon\Carbon::parse($sesi->waktu_tutup)->format('d M Y H:i') : '-' }}</div>
            </div>
            <span class="{{ $sesi->waktu_tutup ? 'bg-red-600' : 'bg-[#788e5e]' }} text-white text-xs font-bold text-center px-4 py-1.5 rounded-xl w-full block shadow-sm transition-colors">
                {{ $sesi->waktu_tutup ? 'Sesi Ditutup' : 'Sesi Aktif' }}
            </span>
        </div>

    </div>

    <div class="border border-black/30 rounded-2xl overflow-hidden bg-[#f7f4e9] shadow-sm">
        <div class="overflow-x-auto">
            <div class="min-w-[650px] md:min-w-0">
                
                <div class="bg-[#c5cb9f] grid grid-cols-4 px-6 py-4 border-b border-black/30 text-left">
                    <div class="font-bold text-gray-900 text-sm">ID Transaksi</div>
                    <div class="font-bold text-gray-900 text-sm">Total Harga</div>
                    <div class="font-bold text-gray-900 text-sm">Jumlah Bayar</div>
                    <div class="font-bold text-gray-900 text-sm">Waktu Transaksi</div>
                </div>

                @if($sesi->transaksiPenjualan && $sesi->transaksiPenjualan->isNotEmpty())
                    @foreach($sesi->transaksiPenjualan->sortByDesc('waktu_transaksi') as $transaksi)
                    <div class="grid grid-cols-4 px-6 py-4 border-b border-black/10 items-center last:border-b-0 hover:bg-black/5 transition">
                        <div class="font-bold text-sm">
                            <a href="{{ route('transaksi.detail', $transaksi->id_transaksi) }}" class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1">
                                <span class="material-icons-outlined !text-[16px]">receipt</span>
                                TRX-{{ $transaksi->id_transaksi }}
                            </a>
                        </div>
                        
                        <div class="font-bold text-gray-900 text-sm">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </div>
                        
                        <div class="font-semibold text-green-700 text-sm">
                            Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}
                        </div>
                        
                        <div class="font-medium text-gray-600 text-sm">
                            {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d M Y H:i') }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="px-6 py-12 text-center text-sm font-bold text-gray-400">
                        Belum ada transaksi di sesi ini.
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection