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
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('bahan-baku.show', $bahanBaku) }}"
        class="p-2 hover:bg-gray-100 rounded-xl transition
            <span class="material-icons-outlined text-gray-500">arrow_back</span>
    </a>
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-800">Edit Belanja</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $bahanBaku->tgl_pembelian->format('d F Y') }}</p>
    </div>
</div>

<form action="{{ route('bahan-baku.update', $bahanBaku) }}" method="POST" x-data="editBahanBaku()">
    @csrf
    @method('PUT')

    {{-- Informasi Pembelian --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-5">
        <div class="flex items-center gap-2 mb-5">
            <span class="material-icons-outlined !text-[20px] text-[#8a7d55]">info</span>
            <h3 class="text-base font-semibold text-gray-800">Informasi Pembelian</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            {{-- Tanggal Pembelian --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                    Tanggal Pembelian
                </label>
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-300 rounded-xl px-3 py-2.5 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition">
                    <span class="material-icons-outlined !text-[18px] text-gray-400">calendar_today</span>
                    <input
                        type="date"
                        name="tgl_pembelian"
                        value="{{ old('tgl_pembelian', $bahanBaku->tgl_pembelian->format('Y-m-d')) }}"
                        class="text-sm outline-none bg-transparent w-full text-gray-700"
                        required
                    >
                </div>
                @error('tgl_pembelian')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                    Keterangan
                </label>
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-300 rounded-xl px-3 py-2.5 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition">
                    <span class="material-icons-outlined !text-[18px] text-gray-400">notes</span>
                    <input
                        type="text"
                        name="keterangan"
                        value="{{ old('keterangan', $bahanBaku->keterangan) }}"
                        placeholder="Contoh: Bahan Baku Soto Batok"
                        class="text-sm outline-none bg-transparent w-full text-gray-700"
                        required
                    >
                </div>
                @error('keterangan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Metode Pembayaran --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                    Metode Pembayaran
                </label>
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-300 rounded-xl px-3 py-2.5 focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition">
                    <span class="material-icons-outlined !text-[18px] text-gray-400">payments</span>
                    <select
                        name="metode_pembayaran"
                        class="text-sm outline-none bg-transparent w-full text-gray-700 cursor-pointer"
                        required
                    >
                        <option value="">Pilih Metode</option>
                        @foreach(['Tunai', 'Transfer', 'QRIS'] as $metode)
                            <option value="{{ $metode }}" {{ old('metode_pembayaran', $bahanBaku->metode_pembayaran) === $metode ? 'selected' : '' }}>
                                {{ $metode }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('metode_pembayaran')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Detail Item Belanja --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-5">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <span class="material-icons-outlined !text-[20px] text-[#8a7d55]">shopping_basket</span>
                <h3 class="text-base font-semibold text-gray-800">Item Belanja</h3>
            </div>
            <button
                type="button"
                @click="addItem"
                class="flex items-center gap-1.5 bg-[#c5cb9f] hover:bg-[#b5bb8f] text-[#5a4a2f] text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
                <span class="material-icons-outlined !text-[16px]">add</span>
                Tambah Item
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#f7f4e9] text-gray-600">
                        <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider w-28">Qty</th>
                        <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wider w-28">Satuan</th>
                        <th class="px-4 py-3 text-right font-semibold text-xs uppercase tracking-wider w-40">Harga Total</th>
                        <th class="px-4 py-3 text-center font-semibold text-xs uppercase tracking-wider w-16">Hapus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="hover:bg-[#fdfcf8] transition">
                            <td class="px-4 py-3">
                                <input
                                    type="text"
                                    :name="`items[${index}][nama_bahan]`"
                                    x-model="item.nama_bahan"
                                    placeholder="Nama bahan baku"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#8a7d55] focus:ring-2 focus:ring-[#8a7d55]/20 transition bg-gray-50"
                                    required
                                >
                            </td>
                            <td class="px-4 py-3">
                                <input
                                    type="number"
                                    :name="`items[${index}][qty]`"
                                    x-model="item.qty"
                                    placeholder="0"
                                    step="0.01"
                                    min="0.01"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#8a7d55] focus:ring-2 focus:ring-[#8a7d55]/20 transition bg-gray-50"
                                    required
                                >
                            </td>
                            <td class="px-4 py-3">
                                <input
                                    type="text"
                                    :name="`items[${index}][satuan]`"
                                    x-model="item.satuan"
                                    placeholder="kg / pcs / ltr"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#8a7d55] focus:ring-2 focus:ring-[#8a7d55]/20 transition bg-gray-50"
                                    required
                                >
                            </td>
                            <td class="px-4 py-3">
                                <input
                                    type="number"
                                    :name="`items[${index}][harga_total]`"
                                    x-model="item.harga_total"
                                    placeholder="0"
                                    min="0"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-[#8a7d55] focus:ring-2 focus:ring-[#8a7d55]/20 transition bg-gray-50 text-right"
                                    required
                                >
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button
                                    type="button"
                                    @click="removeItem(index)"
                                    x-show="items.length > 1"
                                    class="flex items-center justify-center mx-auto w-8 h-8 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition">
                                    <span class="material-icons-outlined !text-[18px]">delete_outline</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr class="bg-[#f7f4e9]">
                        <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Total Pengeluaran</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-gray-800">
                            Rp <span x-text="formatRupiah(totalPengeluaran)"></span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Validation errors for items --}}
    @if($errors->has('items') || $errors->has('items.*') || $errors->has('items.*.*'))
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        <div class="flex items-center gap-2 font-semibold mb-1">
            <span class="material-icons-outlined !text-[16px]">error_outline</span>
            Terdapat kesalahan pada item belanja:
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-xs">
            @foreach($errors->all() as $error)
                @if(str_contains($error, 'item') || str_contains($error, 'Item'))
                    <li>{{ $error }}</li>
                @endif
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('bahan-baku.show', $bahanBaku) }}"
            class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-semibold text-gray-600 hover:bg-gray-50 shadow-sm transition">
            Batal
        </a>
        <button
            type="submit"
            class="flex items-center gap-2 bg-[#8a7d55] hover:bg-[#7a6d45] text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition">
            <span class="material-icons-outlined !text-[18px]">save</span>
            Simpan Perubahan
        </button>
    </div>
</form>

@php
$itemsData = $bahanBaku->details->map(function($d) {
    return [
        'nama_bahan'  => $d->nama_bahan,
        'qty'         => $d->qty,
        'satuan'      => $d->satuan,
        'harga_total' => $d->harga_total,
    ];
})->values()->toArray();
@endphp

<script>
function editBahanBaku() {
    return {
        items: @json($itemsData),

        get totalPengeluaran() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.harga_total) || 0), 0);
        },

        addItem() {
            this.items.push({ nama_bahan: '', qty: '', satuan: '', harga_total: '' });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        formatRupiah(value) {
            return Math.round(value).toLocaleString('id-ID');
        }
    }
}
</script>

@endsection