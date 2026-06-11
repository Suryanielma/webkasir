@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px]">
    <!-- Header -->
    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-950">Sesi Kasir</h2>
            <p class="text-sm font-bold text-gray-500 ">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>
        <div class="flex gap-2.5 relative top-2">
            @if(session('success'))
                <span class="text-green-600 font-bold self-center mr-4">{{ session('success') }}</span>
            @endif
            
            @if(session('error'))
                <span class="text-red-600 font-bold self-center mr-4">{{ session('error') }}</span>
            @endif
            
            @if(!$sesiAktif)
                <form action="{{ route('sesi-kasir.buka') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-1.5 bg-[#c5cb9f] text-black font-semibold rounded border border-gray-400 text-sm hover:bg-[#b8be92] transition-colors">Buka Kasir</button>
                </form>
            @else
                <form action="{{ route('sesi-kasir.tutup') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-1.5 bg-[#F44336] text-white font-semibold rounded border border-gray-400 text-sm hover:bg-red-700 transition-colors">Tutup Kasir</button>
                </form>
            @endif
        </div>
    </div>
    
    <div class="border-t border-black mb-6"></div>

    <!-- Cards Summary -->
    <div class="flex gap-6 mb-8">
        <!-- Card Total Transaksi -->
        <div class="bg-white rounded-lg border border-gray-400 p-5 w-[200px] flex flex-col justify-between shadow-sm">
            <h4 class="font-bold text-sm text-black mb-4">Total Transaksi</h4>
            <p class="text-4xl font-bold  text-black mb-6">{{ $sesiAktif ? $sesiAktif->transaksiPenjualan->count() : 0 }}</p>
            <span class="bg-[#788e5e] text-white text-xs font-semibold text-center px-4 py-1.5 rounded-full w-full block">Selesai</span>
        </div>
        <!-- Card Total Pendapatan -->
        <div class="bg-white rounded-lg border border-gray-400 p-5 w-[240px] flex flex-col justify-between shadow-sm">
            <h4 class="font-bold text-sm text-black mb-4">Total Pendapatan</h4>
            <p class="text-3xl font-bold  text-black mb-6">Rp {{ number_format($sesiAktif ? $sesiAktif->transaksiPenjualan->sum('total_harga') : 0, 0, ',', '.') }}</p>
            <span class="bg-[#4e825a] text-white text-xs font-semibold text-center px-4 py-1.5 rounded-full w-full block">Rupiah (IDR)</span>
        </div>
    </div>

    <!-- Table Container -->
    <div class="border border-black rounded-lg overflow-hidden flex flex-col bg-[#f7f4e9]">
        <!-- Table Header -->
        <div class="bg-[#c5cb9f] grid grid-cols-4 px-6 py-4 border-b border-black">
            <div class="font-bold text-black  text-sm">ID Transaksi</div>
            <div class="font-bold text-black  text-sm">Total Harga</div>
            <div class="font-bold text-black text-sm">Jumlah Bayar</div>
            <div class="font-bold text-black text-sm">Waktu Transaksi</div>
        </div>

        @if($sesiAktif && $sesiAktif->transaksiPenjualan && $sesiAktif->transaksiPenjualan->isNotEmpty())
            @foreach($sesiAktif->transaksiPenjualan->sortByDesc('waktu_transaksi') as $transaksi)
            <!-- Table Row -->
            <div class="grid grid-cols-4 px-6 py-4 border-b border-black items-center">
                <div class="font-bold text-sm "><a href="{{ route('transaksi.detail', $transaksi->id_transaksi) }}" class="text-blue-600 hover:underline">TRX-{{ $transaksi->id_transaksi }}</a></div>
                <div class="font-bold text-black text-sm ">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                <div class="font-bold text-black text-sm ">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</div>
                <div class="font-bold text-black text-sm ">{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d M Y H:i') }}</div>
            </div>
            @endforeach
        @else
            <div class="px-6 py-8 text-center text-sm font-bold  text-gray-500">
                Belum ada transaksi di sesi ini.
            </div>
        @endif
    </div>
</div>
@endsection