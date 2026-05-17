@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px]">
    <!-- Header -->
    <div class="mb-4">
        <h2 class="text-4xl font-bold font-serif text-black mb-1">Buku Kasir</h2>
        <p class="text-sm font-bold text-gray-500 font-serif">Riwayat sesi buka tutup kasir dan transaksi</p>
    </div>
    
    <div class="border-t border-black mb-6"></div>

    <!-- Table Container -->
    <div class="border border-black rounded-lg overflow-hidden flex flex-col bg-[#f7f4e9]">
        <!-- Table Header -->
        <div class="bg-[#c5cb9f] grid grid-cols-6 px-6 py-4 border-b border-black">
            <div class="font-bold text-black font-serif text-sm">ID Sesi</div>
            <div class="font-bold text-black font-serif text-sm">Waktu Buka</div>
            <div class="font-bold text-black font-serif text-sm">Waktu Tutup</div>
            <div class="font-bold text-black font-serif text-sm">Kasir</div>
            <div class="font-bold text-black font-serif text-sm">Total Transaksi</div>
            <div class="font-bold text-black font-serif text-sm">Total Pendapatan</div>
        </div>

        @if($riwayatSesi && $riwayatSesi->isNotEmpty())
            @foreach($riwayatSesi as $sesi)
            <!-- Table Row -->
            <div class="grid grid-cols-6 px-6 py-4 border-b border-black items-center">
                <div class="font-bold text-sm font-serif">
                    <a href="{{ route('buku-kasir.detail', $sesi->id_sesi) }}" class="text-blue-600 hover:underline">SESI-{{ $sesi->id_sesi }}</a>
                </div>
                <div class="font-bold text-black text-sm font-serif">{{ \Carbon\Carbon::parse($sesi->waktu_buka)->format('d M Y H:i') }}</div>
                <div class="font-bold text-black text-sm font-serif">
                    @if($sesi->waktu_tutup)
                        {{ \Carbon\Carbon::parse($sesi->waktu_tutup)->format('d M Y H:i') }}
                    @else
                        <span class="text-green-600">Sesi Aktif</span>
                    @endif
                </div>
                <div class="font-bold text-black text-sm font-serif">{{ $sesi->user->username ?? 'Unknown' }}</div>
                <div class="font-bold text-black text-sm font-serif">{{ $sesi->transaksiPenjualan->count() }} Trx</div>
                <div class="font-bold text-black text-sm font-serif">Rp {{ number_format($sesi->transaksiPenjualan->sum('total_harga'), 0, ',', '.') }}</div>
            </div>
            @endforeach
        @else
            <div class="px-6 py-8 text-center text-sm font-bold font-serif text-gray-500">
                Belum ada riwayat sesi kasir.
            </div>
        @endif
    </div>
</div>
@endsection
