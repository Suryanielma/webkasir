@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px]">
    <!-- Header -->
    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-4xl font-bold font-serif text-black mb-1">Detail Sesi Kasir</h2>
            <p class="text-sm font-bold text-gray-500 font-serif">Sesi Kasir: SESI-{{ $sesi->id_sesi }} | Kasir: {{ $sesi->user->username ?? 'Unknown' }}</p>
        </div>
        <div class="flex gap-2.5 relative top-2">
            <a href="{{ route('buku-kasir') }}" class="px-4 py-1.5 bg-[#c5cb9f] text-black font-semibold rounded border border-gray-400 text-sm hover:bg-[#b8be92] transition-colors">Kembali</a>
        </div>
    </div>
    
    <div class="border-t border-black mb-6"></div>

    <!-- Cards Summary -->
    <div class="flex gap-6 mb-8">
        <!-- Card Total Transaksi -->
        <div class="bg-white rounded-lg border border-gray-400 p-5 w-[200px] flex flex-col justify-between shadow-sm">
            <h4 class="font-bold text-sm text-black mb-4">Total Transaksi</h4>
            <p class="text-4xl font-bold font-serif text-black mb-6">{{ $sesi->transaksiPenjualan->count() }}</p>
            <span class="bg-[#788e5e] text-white text-xs font-semibold text-center px-4 py-1.5 rounded-full w-full block">Selesai</span>
        </div>
        <!-- Card Total Pendapatan -->
        <div class="bg-white rounded-lg border border-gray-400 p-5 w-[240px] flex flex-col justify-between shadow-sm">
            <h4 class="font-bold text-sm text-black mb-4">Total Pendapatan</h4>
            <p class="text-3xl font-bold font-serif text-black mb-6">Rp {{ number_format($sesi->transaksiPenjualan->sum('total_harga'), 0, ',', '.') }}</p>
            <span class="bg-[#4e825a] text-white text-xs font-semibold text-center px-4 py-1.5 rounded-full w-full block">Rupiah (IDR)</span>
        </div>
        <!-- Card Waktu -->
        <div class="bg-white rounded-lg border border-gray-400 p-5 w-[300px] flex flex-col justify-between shadow-sm">
            <h4 class="font-bold text-sm text-black mb-4">Waktu Sesi</h4>
            <div class="flex flex-col gap-2 mb-2">
                <div class="text-sm font-bold"><span class="text-gray-500">Buka:</span> {{ \Carbon\Carbon::parse($sesi->waktu_buka)->format('d M Y H:i') }}</div>
                <div class="text-sm font-bold"><span class="text-gray-500">Tutup:</span> {{ $sesi->waktu_tutup ? \Carbon\Carbon::parse($sesi->waktu_tutup)->format('d M Y H:i') : '-' }}</div>
            </div>
            <span class="{{ $sesi->waktu_tutup ? 'bg-red-600' : 'bg-[#788e5e]' }} text-white text-xs font-semibold text-center px-4 py-1.5 rounded-full w-full block">{{ $sesi->waktu_tutup ? 'Sesi Ditutup' : 'Sesi Aktif' }}</span>
        </div>
    </div>

    <!-- Table Container -->
    <div class="border border-black rounded-lg overflow-hidden flex flex-col bg-[#f7f4e9]">
        <!-- Table Header -->
        <div class="bg-[#c5cb9f] grid grid-cols-4 px-6 py-4 border-b border-black">
            <div class="font-bold text-black font-serif text-sm">ID Transaksi</div>
            <div class="font-bold text-black font-serif text-sm">Total Harga</div>
            <div class="font-bold text-black font-serif text-sm">Jumlah Bayar</div>
            <div class="font-bold text-black font-serif text-sm">Waktu Transaksi</div>
        </div>

        @if($sesi->transaksiPenjualan && $sesi->transaksiPenjualan->isNotEmpty())
            @foreach($sesi->transaksiPenjualan->sortByDesc('waktu_transaksi') as $transaksi)
            <!-- Table Row -->
            <div class="grid grid-cols-4 px-6 py-4 border-b border-black items-center">
                <div class="font-bold text-sm font-serif"><a href="{{ route('transaksi.detail', $transaksi->id_transaksi) }}" class="text-blue-600 hover:underline">TRX-{{ $transaksi->id_transaksi }}</a></div>
                <div class="font-bold text-black text-sm font-serif">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                <div class="font-bold text-black text-sm font-serif">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</div>
                <div class="font-bold text-black text-sm font-serif">{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d M Y H:i') }}</div>
            </div>
            @endforeach
        @else
            <div class="px-6 py-8 text-center text-sm font-bold font-serif text-gray-500">
                Belum ada transaksi di sesi ini.
            </div>
        @endif
    </div>
</div>
@endsection
