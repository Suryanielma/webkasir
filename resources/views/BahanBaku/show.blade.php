@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('bahan-baku.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition">
        <span class="material-icons-outlined text-gray-500">arrow_back</span>
    </a>
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-800">Detail Belanja</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $bahanBaku->tgl_pembelian->format('d F Y') }}</p>
    </div>
</div>

{{-- Info Transaksi --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="material-icons-outlined !text-[18px] text-[#8a7d55]">info</span>
        Informasi Pembelian
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Tanggal Pembelian</p>
            <div class="flex items-center gap-2">
                <span class="material-icons-outlined !text-[16px] text-[#8a7d55]">calendar_today</span>
                <p class="text-sm font-semibold text-gray-800">{{ $bahanBaku->tgl_pembelian->format('d/m/Y') }}</p>
            </div>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Keterangan</p>
            <div class="flex items-center gap-2">
                <span class="material-icons-outlined !text-[16px] text-[#8a7d55]">notes</span>
                <p class="text-sm font-semibold text-gray-800">{{ $bahanBaku->keterangan }}</p>
            </div>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Metode Pembayaran</p>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                {{ $bahanBaku->metode_pembayaran === 'Tunai' ? 'bg-green-100 text-green-700' : ($bahanBaku->metode_pembayaran === 'Transfer' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                <span class="material-icons-outlined !text-[14px]">
                    {{ $bahanBaku->metode_pembayaran === 'Tunai' ? 'payments' : ($bahanBaku->metode_pembayaran === 'Transfer' ? 'account_balance' : 'qr_code_2') }}
                </span>
                {{ $bahanBaku->metode_pembayaran }}
            </span>
        </div>
    </div>
</div>

{{-- Daftar Item --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
        <span class="material-icons-outlined !text-[18px] text-[#8a7d55]">shopping_cart</span>
        <h3 class="font-bold text-gray-800">Daftar Item Belanja</h3>
        <span class="ml-auto bg-[#f7f4e9] text-[#8a7d55] text-xs font-semibold px-2.5 py-0.5 rounded-full border border-[#8a7d55]/20">
            {{ $bahanBaku->details->count() }} item
        </span>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-[#8a7d55] text-white">
                <th class="px-6 py-3 text-center font-semibold w-12">#</th>
                <th class="px-6 py-3 text-left font-semibold">Nama Bahan</th>
                <th class="px-6 py-3 text-center font-semibold">Qty</th>
                <th class="px-6 py-3 text-right font-semibold">Harga Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($bahanBaku->details as $i => $detail)
            <tr class="hover:bg-[#f7f4e9] transition">
                <td class="px-6 py-4 text-center text-gray-400 text-xs">{{ $i + 1 }}.</td>
                <td class="px-6 py-4 font-semibold text-gray-800">{{ $detail->nama_bahan }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="bg-[#f7f4e9] border border-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $detail->qty }} {{ $detail->satuan }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right font-semibold text-gray-800">
                    Rp {{ number_format($detail->harga_total, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Footer Total + Aksi --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Keseluruhan</p>
            <p class="text-3xl font-bold text-gray-900">
                Rp {{ number_format($bahanBaku->total_pengeluaran, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ $bahanBaku->details->count() }} item belanja</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('bahan-baku.edit', $bahanBaku) }}"
                class="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold px-5 py-2.5 rounded-xl border border-blue-200 transition">
                <span class="material-icons-outlined !text-[18px]">edit</span>
                Edit
            </a>
            <form method="POST" action="{{ route('bahan-baku.destroy', $bahanBaku) }}"
                onsubmit="return confirm('Hapus data belanja ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-5 py-2.5 rounded-xl border border-red-200 transition">
                    <span class="material-icons-outlined !text-[18px]">delete</span>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection