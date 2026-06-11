@extends('layouts.app')

@section('content')

@php
    $produkArray = $produk->map(function($p) {
        return [
            'id_produk'   => $p->id_produk,
            'nama_produk' => $p->nama_produk,
            'harga'       => $p->harga,
            'status'      => $p->status,
            'id_kategori' => $p->id_kategori,
            'gambar_url'  => $p->gambar
                ? asset('storage/' . $p->gambar)
                : asset('images/default-menu.png'),
        ];
    })->values();
@endphp

<div x-data="cart()" class="flex flex-col xl:flex-row gap-6 min-h-screen xl:h-[calc(100vh-6rem)]">
    
    <div class="flex-1 flex flex-col min-w-0">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-950 tracking-tight">Menu</h2>
            <div class="relative w-full sm:w-72">
                <input type="text" x-model="searchQuery"
                       placeholder="Cari menu soto atau minuman..."
                       class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-400/70 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] text-gray-800">
                <span class="absolute right-3 top-3 material-icons-outlined text-gray-400 !text-[20px]">search</span>
            </div>
        </div>

        <div class="flex gap-2 mb-6 overflow-x-auto flex-nowrap pb-2 scrollbar-thin">
            <button @click="filterKategori(null)"
                :class="activeKategori === null ? 'bg-[#c5cb9f] text-[#5a4a2f] font-semibold border-[#c5cb9f]' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                class="px-4 py-2 rounded-xl border text-sm flex-shrink-0 whitespace-nowrap transition shadow-sm">
                Semua
            </button>
            @foreach($kategoris as $kat)
                <button @click="filterKategori({{ $kat->id_kategori }})"
                    :class="activeKategori === {{ $kat->id_kategori }} ? 'bg-[#c5cb9f] text-[#5a4a2f] font-semibold border-[#c5cb9f]' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                    class="px-4 py-2 rounded-xl border text-sm flex-shrink-0 whitespace-nowrap transition shadow-sm">
                    {{ $kat->nama_kategori }}
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pr-1 flex-1 items-start content-start min-h-[300px]">
            <template x-for="p in produkTersedia" :key="p.id_produk">
                <div @click="addToCart(p.id_produk, p.nama_produk, p.harga, p.gambar_url)"
                     class="bg-white rounded-2xl p-3 border border-gray-200 shadow-sm flex flex-col items-center cursor-pointer hover:shadow-md hover:border-[#c5cb9f]/60 transition group">
                    <div class="w-full h-28 sm:h-32 rounded-xl overflow-hidden mb-2.5 bg-gray-50 flex-shrink-0">
                        <img :src="p.gambar_url" :alt="p.nama_produk" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <h3 class="text-gray-900 font-semibold text-sm text-center line-clamp-1 flex-1 mb-1" x-text="p.nama_produk"></h3>
                    <p class="text-[#8c6729] font-bold text-sm text-center" x-text="'Rp ' + formatRupiah(p.harga)"></p>
                </div>
            </template>
        </div>

        <template x-if="produkHabis.length > 0">
            <div>
                <div class="border-t border-gray-300/60 my-6 mr-1"></div>
                <h4 class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-wider">Menu Habis (Stok Kosong)</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-4 pr-1 pb-10">
                    <template x-for="p in produkHabis" :key="p.id_produk">
                        <div class="bg-gray-100 rounded-2xl p-3 border border-gray-200 flex flex-col items-center cursor-not-allowed opacity-65 select-none">
                            <div class="w-full h-28 sm:h-32 rounded-xl overflow-hidden mb-2.5 bg-gray-200 grayscale">
                                <img :src="p.gambar_url" :alt="p.nama_produk" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-gray-500 font-semibold text-sm text-center line-clamp-1" x-text="p.nama_produk"></h3>
                            <p class="text-gray-400 font-medium text-sm text-center" x-text="'Rp ' + formatRupiah(p.harga)"></p>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <div class="w-full xl:w-[360px] 2xl:w-[400px] flex flex-col gap-4 flex-shrink-0">
        
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex flex-col h-[350px] xl:flex-1 relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-gray-950">Detail Pesanan</h3>
                <button @click="showInfoModal = true"
                        class="px-3 py-1.5 rounded-xl border border-gray-300 text-xs font-semibold hover:bg-gray-50 transition flex items-center gap-1 text-gray-700">
                    <span class="material-icons-outlined !text-[14px]">edit</span>
                    Edit Info
                </button>
            </div>

            <div class="flex-1 overflow-y-auto pr-1 divide-y divide-gray-100">
                <template x-for="item in items" :key="item.id">
                    <div class="flex gap-3 py-3 first:pt-0 last:pb-0">
                        <img :src="item.image" class="w-14 h-14 object-cover rounded-xl border border-gray-100 flex-shrink-0">
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <h4 class="font-semibold text-sm text-gray-900 truncate" x-text="item.name"></h4>
                                <p class="text-xs text-[#8c6729] font-bold mt-0.5" x-text="'Rp ' + formatRupiah(item.price)"></p>
                            </div>
                            <div class="flex items-center gap-3 mt-1">
                                <button @click="decrement(item.id)" class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition focus:outline-none">
                                    <span class="material-icons-outlined !text-[12px]">remove</span>
                                </button>
                                <span class="text-xs font-bold text-gray-800" x-text="item.qty"></span>
                                <button @click="increment(item.id)" class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition focus:outline-none">
                                    <span class="material-icons-outlined !text-[12px]">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="items.length === 0" class="flex flex-col items-center justify-center h-full text-gray-400 gap-1 py-10">
                    <span class="material-icons-outlined !text-[32px] opacity-45">shopping_basket</span>
                    <p class="text-xs font-medium">Belum ada pesanan masuk</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm flex flex-col">
            <h3 class="text-base font-bold text-gray-950 mb-4">Pembayaran</h3>
            <div class="flex justify-between items-center mb-3">
                <span class="font-bold text-base text-gray-950">TOTAL</span>
                <span class="font-bold text-xl text-gray-950" x-text="'Rp ' + formatRupiah(total)"></span>
            </div>
            
            <div class="border-t border-gray-100 my-3"></div>

            <div class="space-y-2.5">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Tipe Pesanan</span>
                    <span class="text-xs font-semibold bg-[#c5cb9f]/30 text-[#5a4a2f] px-2.5 py-1 rounded-lg" x-text="orderType"></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Atas Nama</span>
                    <span class="text-xs font-semibold bg-[#c5cb9f]/30 text-[#5a4a2f] px-2.5 py-1 rounded-lg truncate max-w-[150px]" x-text="customerName || '-'"></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Kasir</span>
                    <span class="text-xs font-semibold bg-[#c5cb9f]/30 text-[#5a4a2f] px-2.5 py-1 rounded-lg truncate max-w-[150px]" x-text="kasirName || '-'"></span>
                </div>
                <div class="flex justify-between items-center text-sm" x-show="orderType === 'Makan di Tempat'">
                    <span class="text-gray-500 font-medium">No Meja</span>
                    <span class="text-xs font-semibold bg-[#c5cb9f]/30 text-[#5a4a2f] px-2.5 py-1 rounded-lg" x-text="tableNumber || '-'"></span>
                </div>
            </div>

            <div class="border-t border-gray-100 my-4"></div>

            <button @click="showPaymentModal = true" 
                    class="w-full py-3 bg-[#c5cb9f] text-[#5a4a2f] font-bold rounded-xl hover:bg-[#b4ba8e] transition focus:outline-none shadow-sm" 
                    :disabled="items.length === 0" 
                    :class="{'opacity-50 cursor-not-allowed': items.length === 0}">
                Bayar
            </button>
        </div>
    </div>

    <div x-show="showInfoModal"
         x-transition.opacity
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm"
         x-cloak>
        <div @click.away="showInfoModal = false"
             class="bg-[#f7f4e9] rounded-2xl w-full max-w-[400px] mx-4 overflow-hidden shadow-xl border border-[#c5cb9f]"
             x-transition.scale>
            <div class="p-5 border-b border-[#c5cb9f]/60 flex justify-between items-center bg-[#e0e4c6]">
                <h3 class="font-bold text-lg text-black">Informasi Pesanan</h3>
                <button @click="showInfoModal = false" class="text-black hover:text-gray-700">
                    <span class="material-icons-outlined block">close</span>
                </button>
            </div>
            <div class="p-6 flex flex-col gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tipe Pesanan</label>
                    <select x-model="orderType" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white text-gray-800 text-sm">
                        <option value="Makan di Tempat">Makan di Tempat</option>
                        <option value="Bawa Pulang">Bawa Pulang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Atas Nama</label>
                    <input type="text" x-model="customerName" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white text-gray-800 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Kasir</label>
                    <input type="text" x-model="kasirName" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white text-gray-800 text-sm" placeholder="Nama kasir...">
                </div>
                <div x-show="orderType === 'Makan di Tempat'" x-transition>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">No Meja</label>
                    <input type="number" x-model="tableNumber" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white text-gray-800 text-sm">
                </div>
            </div>
            <div class="p-4 border-t border-[#c5cb9f]/40 flex justify-end bg-gray-50/50">
                <button @click="showInfoModal = false" class="px-5 py-2 bg-[#c5cb9f] hover:bg-[#b0b885] text-[#5a4a2f] font-bold text-sm rounded-xl transition shadow-sm">
                    Simpan Info
                </button>
            </div>
        </div>
    </div>

    <div x-show="showPaymentModal"
         x-transition.opacity
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm"
         x-cloak>
        <div @click.away="showPaymentModal = false"
             class="bg-[#f7f4e9] rounded-2xl w-full max-w-[400px] mx-4 overflow-hidden shadow-xl border border-[#c5cb9f]"
             x-transition.scale>
            <div class="p-5 border-b border-[#c5cb9f]/60 flex justify-between items-center bg-[#e0e4c6]">
                <h3 class="font-bold text-lg text-black">Proses Pembayaran</h3>
                <button @click="showPaymentModal = false" class="text-black hover:text-gray-700">
                    <span class="material-icons-outlined block">close</span>
                </button>
            </div>
            <form action="{{ route('transaksi.checkout') }}" method="POST">
                @csrf
                <div class="p-6 flex flex-col gap-4">
                    <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-gray-200">
                        <span class="text-sm font-semibold text-gray-500">Total Tagihan:</span>
                        <span class="text-xl font-bold text-gray-900" x-text="'Rp ' + formatRupiah(total)"></span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jumlah Uang Bayar</label>
                        <input type="number" x-model.number="payAmount" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white text-gray-900 font-bold text-base" required>
                    </div>
                    <div class="flex justify-between items-center p-2">
                        <span class="text-sm font-semibold text-gray-600">Kembalian:</span>
                        <span class="text-lg font-bold" :class="payAmount >= total ? 'text-green-600' : 'text-red-600'" x-text="'Rp ' + formatRupiah(Math.max(0, payAmount - total))"></span>
                    </div>
                </div>

                <input type="hidden" name="total_harga" :value="total">
                <input type="hidden" name="bayar" :value="payAmount">
                <input type="hidden" name="kembalian" :value="Math.max(0, payAmount - total)">
                <input type="hidden" name="tipe_pesanan" :value="orderType">
                <input type="hidden" name="nama_pembeli" :value="customerName">
                <input type="hidden" name="nomor_meja" :value="tableNumber">
                <input type="hidden" name="nama_kasir" :value="kasirName">
                <input type="hidden" name="items" :value="JSON.stringify(items.map(i => ({ id_produk: i.id, qty: i.qty, harga: i.price })))">

                <div class="p-4 border-t border-[#c5cb9f]/40 flex justify-end gap-2 bg-gray-50/50">
                    <button type="button" @click="showPaymentModal = false" class="px-4 py-2 border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-[#c5cb9f] hover:bg-[#b0b885] text-[#5a4a2f] font-bold text-sm rounded-xl transition shadow-sm" :disabled="payAmount < total" :class="{'opacity-50 cursor-not-allowed': payAmount < total}">
                        Selesai & Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const produkData = {!! json_encode($produkArray) !!};

    document.addEventListener('alpine:init', () => {
        Alpine.data('cart', () => ({
            items: [],
            orderType: 'Makan di Tempat',
            customerName: '',
            tableNumber: '',
            kasirName: '{{ $namaKasirDefault }}',
            showInfoModal: false,
            showPaymentModal: false,
            payAmount: 0,

            allProduk: produkData,
            activeKategori: null,
            searchQuery: '',

            get produkTersedia() {
                return this.filtered().filter(p => p.status.toLowerCase() === 'tersedia');
            },

            get produkHabis() {
                return this.filtered().filter(p => p.status.toLowerCase() !== 'tersedia');
            },

            filtered() {
                return this.allProduk.filter(p => {
                    const matchKat = this.activeKategori === null || p.id_kategori == this.activeKategori;
                    const matchSearch = p.nama_produk.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return matchKat && matchSearch;
                });
            },

            filterKategori(id) {
                this.activeKategori = id;
            },

            get total() {
                return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            addToCart(id, name, price, image) {
                const existing = this.items.find(i => i.id === id);
                if (existing) {
                    existing.qty++;
                } else {
                    this.items.push({ id, name, price, image, qty: 1 });
                }
            },

            increment(id) {
                const item = this.items.find(i => i.id === id);
                if (item) item.qty++;
            },

            decrement(id) {
                const item = this.items.find(i => i.id === id);
                if (item) {
                    if (item.qty > 1) {
                        item.qty--;
                    } else {
                        this.items = this.items.filter(i => i.id !== id);
                    }
                }
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        }))
    })
</script>

@if(session('cetak_struk'))
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const url = "{{ route('transaksi.struk', session('cetak_struk')) }}";
        window.open(url, "StrukPembayaran", "width=400,height=600,left=200,top=100");
    });
</script>
@endif
@endpush
@endsection