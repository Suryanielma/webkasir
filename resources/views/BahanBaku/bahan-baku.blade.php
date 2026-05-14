@extends('layouts.app')

@section('content')

{{-- Flash Message --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
    class="mb-4 flex items-center gap-2 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-sm">
    <span class="material-icons-outlined !text-[18px]">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-800">Bahan Baku</h2>
        <p class="text-sm text-gray-500 mt-0.5">Manajemen pembelian bahan baku</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('bahan-baku.index') }}" class="flex flex-wrap items-center gap-2">
            <div class="flex items-center bg-white border border-gray-300 rounded-xl px-3 py-2 shadow-sm gap-2 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition">
                <span class="material-icons-outlined !text-[18px] text-gray-400">search</span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari keterangan..."
                    class="text-sm outline-none bg-transparent w-44"
                >
            </div>
            <div class="flex items-center bg-white border border-gray-300 rounded-xl px-3 py-2 shadow-sm gap-2 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition">
                <span class="material-icons-outlined !text-[18px] text-gray-400">calendar_today</span>
                <input
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                    class="text-sm outline-none bg-transparent"
                >
            </div>
            <button type="submit" class="bg-[#c5cb9f] hover:bg-[#b5bb8f] text-[#5a4a2f] text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition">
                Filter
            </button>
            @if(request('search') || request('tanggal'))
            <a href="{{ route('bahan-baku.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-xl border border-gray-300 bg-white transition">
                Reset
            </a>
            @endif
        </form>

        {{-- Tambah Belanja --}}
        <a href="{{ route('bahan-baku.create') }}"
            class="flex items-center gap-2 bg-[#c5cb9f] hover:bg-[#b5bb8f] text-[#5a4a2f] text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
            <span class="material-icons-outlined !text-[18px]">add</span>
            Tambah Belanja
        </a>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-[#8a7d55] text-white">
                <th class="px-6 py-4 text-left font-semibold">Tgl Pembelian</th>
                <th class="px-6 py-4 text-left font-semibold">Keterangan</th>
                <th class="px-6 py-4 text-left font-semibold">Metode</th>
                <th class="px-6 py-4 text-right font-semibold">Total Pengeluaran</th>
                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($bahanBakus as $item)
            <tr class="hover:bg-[#f7f4e9] transition">
                <td class="px-6 py-4 text-gray-700">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-outlined !text-[16px] text-[#8a7d55]">calendar_today</span>
                        <span class="font-semibold">{{ $item->tgl_pembelian->format('d/m/Y') }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-700 font-medium">
                    {{ $item->keterangan }}
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                        {{ $item->metode_pembayaran === 'Tunai' ? 'bg-green-100 text-green-700' : ($item->metode_pembayaran === 'Transfer' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ $item->metode_pembayaran }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right font-semibold text-gray-800">
                    Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('bahan-baku.show', $item) }}"
                        class="inline-flex items-center gap-1 text-[#8a7d55] hover:text-white hover:bg-[#8a7d55] border border-[#8a7d55] text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                        <span class="material-icons-outlined !text-[14px]">visibility</span>
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                    <span class="material-icons-outlined !text-[48px] block mb-2 text-gray-300">inventory_2</span>
                    <p class="text-sm font-medium text-gray-400">Belum ada data belanja bahan baku.</p>
                    <a href="{{ route('bahan-baku.create') }}" class="inline-flex items-center gap-1 mt-3 text-[#8a7d55] text-sm font-semibold hover:underline">
                        <span class="material-icons-outlined !text-[16px]">add_circle_outline</span>
                        Tambah sekarang
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($bahanBakus->hasPages())
<div class="mt-4">
    {{ $bahanBakus->links() }}
</div>
@endif

@endsection