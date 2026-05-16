@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px]">
    <!-- Header -->
    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-4xl font-bold font-serif text-black mb-1">Detail Transaksi TRX-{{ $transaksi->id_transaksi }}</h2>
            <p class="text-sm font-bold text-gray-500 font-serif">{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') }}</p>
        </div>
        <div class="flex gap-2.5 relative top-2">
            <a href="{{ route('sesi-kasir') }}" class="px-4 py-1.5 bg-[#c5cb9f] text-black font-semibold rounded border border-gray-400 text-sm hover:bg-[#b8be92] transition-colors">Kembali</a>
        </div>
    </div>
    
    <div class="border-t border-black mb-6"></div>

    <!-- Info Transaksi -->
    <div class="bg-white rounded-lg border border-gray-400 p-6 mb-8 shadow-sm font-serif flex justify-between">
        <div>
            <p class="text-sm text-gray-600 mb-1">Kasir</p>
            <p class="font-bold text-black">{{ $transaksi->sesiKasir->user->name ?? 'Unknown' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Total Belanja</p>
            <p class="font-bold text-black">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Bayar</p>
            <p class="font-bold text-green-700">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600 mb-1">Kembalian</p>
            <p class="font-bold text-red-700">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Table Container -->
    <div class="border border-black rounded-lg overflow-hidden flex flex-col bg-[#f7f4e9]">
        <!-- Table Header -->
        <div class="bg-[#c5cb9f] grid grid-cols-4 px-6 py-4 border-b border-black">
            <div class="font-bold text-black font-serif text-sm">Produk</div>
            <div class="font-bold text-black font-serif text-sm">Harga Satuan</div>
            <div class="font-bold text-black font-serif text-sm">Qty</div>
            <div class="font-bold text-black font-serif text-sm">Subtotal</div>
        </div>

        @if($transaksi->detailTransaksi && $transaksi->detailTransaksi->isNotEmpty())
            @foreach($transaksi->detailTransaksi as $detail)
            <!-- Table Row -->
            <div class="grid grid-cols-4 px-6 py-4 border-b border-black items-center last:border-b-0">
                <div class="font-bold text-black text-sm font-serif">{{ $detail->produk ? $detail->produk->nama_produk : 'Produk tidak ditemukan' }}</div>
                <div class="font-bold text-black text-sm font-serif">Rp {{ number_format($detail->produk ? $detail->produk->harga : 0, 0, ',', '.') }}</div>
                <div class="font-bold text-black text-sm font-serif">{{ $detail->jumlah }}</div>
                <div class="font-bold text-black text-sm font-serif">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach
        @else
            <div class="px-6 py-8 text-center text-sm font-bold font-serif text-gray-500">
                Tidak ada detail produk.
            </div>
        @endif
    </div>
</div>
@endsection
