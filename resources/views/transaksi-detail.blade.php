@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px] space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <div>
            <h2 class="text-2xl md:text-4xl font-bold text-black mb-1">Detail Transaksi TRX-{{ $transaksi->id_transaksi }}</h2>
            <p class="text-sm font-semibold text-gray-500">{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') }}</p>
        </div>
        <div class="w-full sm:w-auto flex justify-end">
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto text-center px-4 py-2 bg-[#c5cb9f] text-black font-bold rounded-xl border border-gray-400/60 text-sm hover:bg-[#b8be92] transition shadow-sm">
                Kembali
            </a>
        </div>
    </div>
    
    <div class="border-t border-black mb-6"></div>

    <div class="bg-white rounded-2xl border border-gray-400 p-6 mb-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-7 grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Kasir</p>
                <p class="font-bold text-black text-sm md:text-base">{{ $transaksi->nama_kasir ?? $transaksi->sesiKasir->user->username ?? 'Unknown' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tipe Pesanan</p>
                <p class="font-bold text-black text-sm md:text-base">{{ $transaksi->tipe_pesanan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nama Pembeli</p>
                <p class="font-bold text-black text-sm md:text-base">{{ $transaksi->nama_pembeli ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">No Meja</p>
                <p class="font-bold text-black text-sm md:text-base">{{ $transaksi->nomor_meja ?? '-' }}</p>
            </div>
        </div>
        
        <div class="lg:col-span-5 flex flex-col gap-3 justify-center border-t lg:border-t-0 lg:border-l border-gray-300 pt-6 lg:pt-0 lg:pl-6">
            <div class="flex justify-between items-center">
                <p class="text-sm font-medium text-gray-500">Total Belanja</p>
                <p class="font-bold text-black text-base md:text-lg">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm font-medium text-gray-500">Bayar</p>
                <p class="font-bold text-green-700 text-base md:text-lg">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</p>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-sm font-medium text-gray-500">Kembalian</p>
                <p class="font-bold text-red-700 text-base md:text-lg">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>
            </div>
        </div>

    </div>

    <div class="border border-black rounded-2xl overflow-hidden bg-[#f7f4e9] shadow-sm">
        <div class="overflow-x-auto">
            <div class="min-w-[600px] md:min-w-0">
                
                <div class="bg-[#c5cb9f] grid grid-cols-4 px-6 py-4 border-b border-black">
                    <div class="font-bold text-black text-sm">Produk</div>
                    <div class="font-bold text-black text-sm">Harga Satuan</div>
                    <div class="font-bold text-black text-sm">Qty</div>
                    <div class="font-bold text-black text-sm">Subtotal</div>
                </div>

                @if($transaksi->detailTransaksi && $transaksi->detailTransaksi->isNotEmpty())
                    @foreach($transaksi->detailTransaksi as $detail)
                    <div class="grid grid-cols-4 px-6 py-4 border-b border-black items-center last:border-b-0 hover:bg-black/5 transition">
                        <div class="font-semibold text-gray-800 text-sm">{{ $detail->produk ? $detail->produk->nama_produk : 'Produk tidak ditemukan' }}</div>
                        <div class="font-medium text-gray-600 text-sm">Rp {{ number_format($detail->produk ? $detail->produk->harga : 0, 0, ',', '.') }}</div>
                        <div class="font-bold text-gray-700 text-sm">{{ $detail->jumlah }}</div>
                        <div class="font-bold text-gray-900 text-sm">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="px-6 py-12 text-center text-sm font-bold text-gray-400">
                        Tidak ada detail produk.
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection