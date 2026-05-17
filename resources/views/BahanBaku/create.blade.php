@extends('layouts.app')

@section('content')

<div x-data="bahanBakuForm()" x-init="init()">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('bahan-baku.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition">
            <span class="material-icons-outlined text-gray-500">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-800">Input Data Belanja</h2>
            <p class="text-sm text-gray-500 mt-0.5">Tambah pembelian bahan baku baru</p>
        </div>
    </div>

    <form method="POST" action="{{ route('bahan-baku.store') }}" @submit.prevent="submitForm">
        @csrf

        {{-- Info Header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-icons-outlined !text-[18px] text-[#8a7d55]">info</span>
                Informasi Pembelian
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Tanggal Pembelian</label>
                    <div class="flex items-center gap-2 border border-gray-300 rounded-xl px-3 py-2.5 bg-white focus-within:border-[#8a7d55] focus-within:ring-2 focus-within:ring-[#8a7d55]/20 transition">
                        <span class="material-icons-outlined !text-[18px] text-gray-400">calendar_today</span>
                        <input
                            type="date"
                            x-model="form.tgl_pembelian"
                            name="tgl_pembelian"
                            class="text-sm outline-none bg-transparent flex-1"
                            required
                        >
                    </div>
                </div>
                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Keterangan</label>
                    <input
                        type="text"
                        x-model="form.keterangan"
                        name="keterangan"
                        placeholder="cth. Bahan baku soto batok"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#8a7d55]/20 focus:border-[#8a7d55] transition"
                        required
                    >
                </div>
                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Metode Pembayaran</label>
                    <div class="relative">
                        <select
                            x-model="form.metode_pembayaran"
                            name="metode_pembayaran"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#8a7d55]/20 focus:border-[#8a7d55] transition appearance-none bg-white"
                        >
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                        <span class="material-icons-outlined !text-[18px] text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Item --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6 relative">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="material-icons-outlined !text-[18px] text-[#8a7d55]">shopping_cart</span>
                    Daftar Item Belanja
                </h3>
                <button
                    type="button"
                    @click="openModal()"
                    class="flex items-center gap-2 bg-[#c5cb9f] hover:bg-[#b5bb8f] text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
                    <span class="material-icons-outlined !text-[18px]">add</span>
                    Tambah Item
                </button>
            </div>

            {{-- Tabel Items --}}
            <div class="rounded-xl overflow-hidden border border-gray-200">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#8a7d55] text-white">
                            <th class="px-4 py-3 text-center font-semibold w-12">#</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Bahan</th>
                            <th class="px-4 py-3 text-center font-semibold">Qty</th>
                            <th class="px-4 py-3 text-right font-semibold">Harga Total</th>
                            <th class="px-4 py-3 text-center font-semibold w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-[#f7f4e9] transition">
                                <td class="px-4 py-3 text-center text-gray-500 text-xs" x-text="index + 1 + '.'"></td>
                                <td class="px-4 py-3 font-semibold text-gray-800" x-text="item.nama_bahan"></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-[#f7f4e9] border border-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full"
                                        x-text="item.qty + ' ' + item.satuan"></span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800"
                                    x-text="'Rp ' + formatRupiah(item.harga_total)"></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="editItem(index)"
                                            class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition">
                                            <span class="material-icons-outlined !text-[16px]">edit</span>
                                        </button>
                                        <button type="button" @click="removeItem(index)"
                                            class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition">
                                            <span class="material-icons-outlined !text-[16px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="items.length === 0">
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <span class="material-icons-outlined !text-[40px] block mb-2 text-gray-300">add_shopping_cart</span>
                                    <p class="text-sm text-gray-400">Belum ada item. Klik "+ Tambah Item" untuk menambahkan.</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Modal Input Bahan --}}
            <div
                x-show="showModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute top-16 right-6 z-10 bg-white rounded-2xl shadow-xl border border-gray-200 p-5 w-80"
                @click.outside="closeModal()"
                style="display: none;"
            >
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <span class="material-icons-outlined !text-[16px] text-[#8a7d55]">add_circle_outline</span>
                        <span x-text="editingIndex !== null ? 'Edit Bahan' : 'Input Bahan'"></span>
                    </h4>
                    <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <span class="material-icons-outlined !text-[18px]">close</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Nama Bahan</label>
                        <input
                            type="text"
                            x-model="modalItem.nama_bahan"
                            placeholder="cth. Ayam kampung"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#8a7d55]/20 focus:border-[#8a7d55] transition"
                        >
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Quantity</label>
                            <input
                                type="number"
                                x-model="modalItem.qty"
                                placeholder="cth. 2"
                                min="0.01"
                                step="0.01"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#8a7d55]/20 focus:border-[#8a7d55] transition"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Satuan</label>
                            <div class="relative">
                                <select
                                    x-model="modalItem.satuan"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#8a7d55]/20 focus:border-[#8a7d55] transition bg-white appearance-none"
                                >
                                    <option value="Kg">Kg</option>
                                    <option value="Liter">Liter</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Ikat">Ikat</option>
                                    <option value="Butir">Butir</option>
                                    <option value="Bungkus">Bungkus</option>
                                </select>
                                <span class="material-icons-outlined !text-[14px] text-gray-400 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Harga Total</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-[#8a7d55]/20 focus-within:border-[#8a7d55] transition">
                            <span class="bg-[#f7f4e9] border-r border-gray-300 px-2.5 py-2 text-sm text-gray-500 font-medium">Rp</span>
                            <input
                                type="number"
                                x-model="modalItem.harga_total"
                                placeholder="20000"
                                min="0"
                                class="flex-1 px-3 py-2 text-sm outline-none"
                            >
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <button
                        type="button"
                        @click="saveItem()"
                        class="flex-1 bg-[#8a7d55] hover:bg-[#6e6340] text-white text-sm font-semibold py-2 rounded-lg transition">
                        Simpan
                    </button>
                    <button
                        type="button"
                        @click="closeModal()"
                        class="flex-1 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold py-2 rounded-lg border border-gray-300 transition">
                        Batal
                    </button>
                </div>

                <p x-show="modalError" x-text="modalError" class="text-red-500 text-xs mt-2"></p>
            </div>
        </div>

        {{-- Total & Action --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Keseluruhan</p>
                    <p class="text-3xl font-bold text-gray-900" x-text="'Rp ' + formatRupiah(totalKeseluruhan)">Rp 0</p>
                    <p class="text-xs text-gray-400 mt-1" x-text="items.length + ' item belanja'"></p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('bahan-baku.index') }}"
                        class="bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl border border-gray-300 transition">
                        Batal
                    </a>
                    <button
                        type="button"
                        @click="submitForm()"
                        :disabled="items.length === 0"
                        class="flex items-center gap-2 bg-[#8a7d55] hover:bg-[#6e6340] disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition shadow-sm">
                        <span class="material-icons-outlined !text-[18px]">save</span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <div id="hidden-inputs"></div>
    </form>
</div>

@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function bahanBakuForm() {
    return {
        form: {
            tgl_pembelian: new Date().toISOString().split('T')[0],
            keterangan: '',
            metode_pembayaran: 'Tunai',
        },
        items: [],
        showModal: false,
        editingIndex: null,
        modalItem: { nama_bahan: '', qty: '', satuan: 'Kg', harga_total: '' },
        modalError: '',

        init() {},

        get totalKeseluruhan() {
            return this.items.reduce((sum, i) => sum + parseFloat(i.harga_total || 0), 0);
        },

        formatRupiah(val) {
            return Number(val).toLocaleString('id-ID');
        },

        openModal() {
            this.editingIndex = null;
            this.modalItem = { nama_bahan: '', qty: '', satuan: 'Kg', harga_total: '' };
            this.modalError = '';
            this.showModal = true;
        },

        editItem(index) {
            this.editingIndex = index;
            this.modalItem = { ...this.items[index] };
            this.modalError = '';
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.modalError = '';
        },

        saveItem() {
            if (!this.modalItem.nama_bahan.trim()) {
                this.modalError = 'Nama bahan wajib diisi.';
                return;
            }
            if (!this.modalItem.qty || this.modalItem.qty <= 0) {
                this.modalError = 'Quantity harus lebih dari 0.';
                return;
            }
            if (!this.modalItem.harga_total || this.modalItem.harga_total < 0) {
                this.modalError = 'Harga total tidak valid.';
                return;
            }

            const item = {
                nama_bahan: this.modalItem.nama_bahan.trim(),
                qty: parseFloat(this.modalItem.qty),
                satuan: this.modalItem.satuan,
                harga_total: parseFloat(this.modalItem.harga_total),
            };

            if (this.editingIndex !== null) {
                this.items[this.editingIndex] = item;
                this.items = [...this.items];
            } else {
                this.items.push(item);
            }

            this.closeModal();
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        submitForm() {
            if (this.items.length === 0) {
                alert('Tambahkan minimal satu item bahan baku.');
                return;
            }

            const container = document.getElementById('hidden-inputs');
            container.innerHTML = '';

            const addInput = (name, value) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                container.appendChild(input);
            };

            addInput('tgl_pembelian', this.form.tgl_pembelian);
            addInput('keterangan', this.form.keterangan);
            addInput('metode_pembayaran', this.form.metode_pembayaran);

            this.items.forEach((item, i) => {
                addInput(`items[${i}][nama_bahan]`, item.nama_bahan);
                addInput(`items[${i}][qty]`, item.qty);
                addInput(`items[${i}][satuan]`, item.satuan);
                addInput(`items[${i}][harga_total]`, item.harga_total);
            });

            container.closest('form').submit();
        }
    }
}
</script>
@endpush