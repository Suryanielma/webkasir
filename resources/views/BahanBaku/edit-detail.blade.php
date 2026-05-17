@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('bahan-baku.show', $bahanBaku) }}" class="p-2 hover:bg-gray-100 rounded-xl transition">
            <span class="material-icons-outlined text-gray-500">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-800">Edit Item Belanja</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $bahanBaku->tgl_pembelian->format('d F Y') }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('bahan-baku.detail.update', [$bahanBaku, $detail]) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Bahan --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Nama Bahan</label>
                <input type="text"
                    name="nama_bahan"
                    value="{{ old('nama_bahan', $detail->nama_bahan) }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#8a7d55] focus:ring-2 focus:ring-[#8a7d55]/20 transition @error('nama_bahan') border-red-500 @enderror"
                    required>
                @error('nama_bahan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Qty dan Satuan --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Qty</label>
                    <input type="number"
                        name="qty"
                        value="{{ old('qty', $detail->qty) }}"
                        step="0.01"
                        min="0.01"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#8a7d55] focus:ring-2 focus:ring-[#8a7d55]/20 transition @error('qty') border-red-500 @enderror"
                        required>
                    @error('qty')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Satuan</label>
                    <input type="text"
                        name="satuan"
                        value="{{ old('satuan', $detail->satuan) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-[#8a7d55] focus:ring-2 focus:ring-[#8a7d55]/20 transition @error('satuan') border-red-500 @enderror"
                        required>
                    @error('satuan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Harga Total --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">Harga Total</label>
                <div class="flex items-center gap-2 border border-gray-300 rounded-xl px-4 py-2.5 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition @error('harga_total') border-red-500 @enderror">
                    <span class="text-gray-400">Rp</span>
                    <input type="number"
                        name="harga_total"
                        value="{{ old('harga_total', $detail->harga_total) }}"
                        step="1"
                        min="0"
                        class="flex-1 px-2 py-1 text-sm outline-none bg-transparent"
                        required>
                </div>
                @error('harga_total')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-4">
                <button type="submit"
                    class="flex-1 bg-[#8a7d55] hover:bg-[#7a6d45] text-white font-semibold px-6 py-2.5 rounded-xl transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('bahan-baku.show', $bahanBaku) }}"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
