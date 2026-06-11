@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Flash Message --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="mb-4 flex items-center gap-2 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-sm shadow-sm">
        <span class="material-icons-outlined !text-[18px]">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    {{-- Header Section: Fleksibel Penuh dari HP sampai Monitor Lebar --}}
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-950">Bahan Baku</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manajemen pembelian bahan baku</p>
        </div>
        
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 w-full xl:w-auto">
            
            {{-- Search & Filter Form --}}
            <form method="GET" action="{{ route('bahan-baku.index') }}" class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 flex-1 xl:flex-none">
                
                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-3 py-2.5 shadow-sm gap-2 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition flex-1 sm:flex-none">
                    <span class="material-icons-outlined !text-[18px] text-gray-400">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari keterangan..."
                        class="text-sm outline-none bg-transparent w-full sm:w-44 text-gray-800"
                    >
                </div>

                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-3 py-2.5 shadow-sm gap-2 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition flex-1 sm:flex-none">
                    <span class="material-icons-outlined !text-[18px] text-gray-400">calendar_today</span>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ request('tanggal') }}"
                        class="text-sm outline-none bg-transparent w-full text-gray-800"
                    >
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none bg-[#c5cb9f] hover:bg-[#b5bb8f] text-[#5a4a2f] text-sm font-bold px-5 py-2.5 rounded-xl shadow-sm transition cursor-pointer">
                        Filter
                    </button>
                    
                    @if(request('search') || request('tanggal'))
                    <a href="{{ route('bahan-baku.index') }}" class="flex-1 sm:flex-none text-center text-sm font-semibold text-gray-600 hover:text-gray-800 px-4 py-2.5 rounded-xl border border-gray-300 bg-white transition shadow-sm">
                        Reset
                    </a>
                    @endif
                </div>
            </form>

            {{-- Tombol Tambah Belanja --}}
            <a href="{{ route('bahan-baku.create') }}"
                class="flex items-center justify-center gap-2 bg-[#c5cb9f] hover:bg-[#b5bb8f] text-[#5a4a2f] text-sm font-bold px-4 py-2.5 rounded-xl shadow-sm transition w-full sm:w-auto">
                <span class="material-icons-outlined !text-[18px]">add</span>
                Tambah Belanja
            </a>
        </div>
    </div>

    {{-- Tabel Kontainer Responsif: Proteksi Menggunakan Scroll X --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/70 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[750px] md:min-w-0">
                <thead>
                    <tr class="bg-[#8a7d55] text-white text-left">
                        <th class="px-6 py-4 font-bold">Tgl Pembelian</th>
                        <th class="px-6 py-4 font-bold">Keterangan</th>
                        <th class="px-6 py-4 font-bold">Metode</th>
                        <th class="px-6 py-4 font-bold text-right">Total Pengeluaran</th>
                        <th class="px-6 py-4 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bahanBakus as $item)
                    <tr class="hover:bg-[#f7f4e9]/50 transition">
                        <td class="px-6 py-4 text-gray-700">
                            <div class="flex items-center gap-2">
                                <span class="material-icons-outlined !text-[16px] text-[#8a7d55]">calendar_today</span>
                                <span class="font-semibold">{{ $item->tgl_pembelian->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium max-w-xs truncate">
                            {{ $item->keterangan }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold shadow-sm
                                {{ $item->metode_pembayaran === 'Tunai' ? 'bg-green-50 text-green-700 border border-green-200' : ($item->metode_pembayaran === 'Transfer' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                {{ $item->metode_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-950">
                            Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('bahan-baku.show', $item) }}"
                                class="inline-flex items-center gap-1 text-[#8a7d55] hover:text-white hover:bg-[#8a7d55] border border-[#8a7d55] text-xs font-bold px-3 py-1.5 rounded-xl transition shadow-sm">
                                <span class="material-icons-outlined !text-[14px]">visibility</span>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-icons-outlined !text-[48px] text-gray-300">inventory_2</span>
                                <p class="text-sm font-bold text-gray-400">Belum ada data belanja bahan baku.</p>
                                <a href="{{ route('bahan-baku.create') }}" class="inline-flex items-center gap-1 mt-2 text-[#8a7d55] text-sm font-bold hover:underline">
                                    <span class="material-icons-outlined !text-[16px]">add_circle_outline</span>
                                    Tambah sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Area --}}
    @if($bahanBakus->hasPages())
    <div class="mt-4 px-2">
        {{ $bahanBakus->links() }}
    </div>
    @endif

</div>
@endsection