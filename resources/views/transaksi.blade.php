@extends('layouts.app')

@section('content')
<div x-data="cart()" class="flex gap-6 h-[calc(100vh-4rem)]">
    <!-- Area Menu (Kiri) -->
    <div class="flex-1 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold font-serif text-black">Menu</h2>
            <div class="relative w-72">
                <form action="{{ route('transaksi') }}" method="GET">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari" class="w-full pl-4 pr-10 py-2 rounded-lg border border-gray-400 bg-transparent focus:outline-none focus:ring-1 focus:ring-[#6a4f21]">
                    <button type="submit" class="absolute right-3 top-2.5">    
                        <span class="material-icons-outlined text-black !text-[20px]">search</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="flex gap-3 mb-6 overflow-x-auto">
            <a href="{{ route('transaksi') }}" class="px-4 py-1.5 rounded border border-gray-400 {{ !request('kategori') ? 'bg-[#c5cb9f] text-black font-medium' : 'bg-transparent text-black' }}">Semua</a>
            @foreach($kategoris as $kat)
                <a href="{{ route('transaksi', ['kategori' => $kat->id_kategori]) }}" class="px-4 py-1.5 rounded border border-gray-400 {{ request('kategori') == $kat->id_kategori ? 'bg-[#c5cb9f] text-black font-medium' : 'bg-transparent text-black' }}">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
        </div>

        <!-- Grid Menu -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pr-2 pb-10 border-b border-gray-300">
            @foreach($produk as $p)
                <div @click="addToCart({{ $p->id_produk }}, '{{ addslashes($p->nama_produk) }}', {{ $p->harga }}, '{{ $p->gambar ? asset('storage/' . $p->gambar) : asset('images/default-menu.png') }}')" class="bg-[#d2d5b5] rounded-lg p-3 border border-gray-400/60 flex flex-col items-center cursor-pointer hover:shadow-md transition-shadow">
                    <img src="{{ $p->gambar ? asset('storage/' . $p->gambar) : asset('images/default-menu.png') }}" alt="{{ $p->nama_produk }}" class="w-full h-32 object-cover rounded-md mb-2">
                    <h3 class="text-black font-medium text-center">{{ $p->nama_produk }}</h3>
                    <p class="text-[#8c6729] font-semibold text-center">Rp.{{ number_format($p->harga, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Area Pesanan (Kanan) -->
    <div class="w-[30%] min-w-[320px] flex flex-col gap-4">
        <!-- Detail Pesanan -->
        <div class="bg-[#f7f4e9] rounded-2xl p-5 border shadow-sm flex flex-col flex-1 relative" style="border-color: #d1d5db;">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold font-serif">Detail Pesanan</h3>
                <div class="relative" @click.away="showOrderTypeDropdown = false">
                    <button @click="showOrderTypeDropdown = !showOrderTypeDropdown" 
                            class="px-3 py-1 rounded-full text-xs font-semibold bg-transparent border transition-colors"
                            :class="orderType === 'Makan di Tempat' ? 'text-green-700 border-green-700 hover:bg-green-50 bg-[#eef7ec]' : 'text-red-600 border-red-600 hover:bg-red-50 bg-[#fde9e9]'"
                            x-text="orderType">
                    </button>
                    
                    <div x-show="showOrderTypeDropdown" 
                         x-transition
                         class="absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-10 overflow-hidden">
                        <button @click="orderType = 'Makan di Tempat'; showOrderTypeDropdown = false" 
                                class="w-full text-left px-4 py-2 text-xs font-semibold text-green-700 hover:bg-green-50 border-b border-gray-100">
                            Makan di Tempat
                        </button>
                        <button @click="orderType = 'Bawa Pulang'; showOrderTypeDropdown = false" 
                                class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                            Bawa Pulang
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto pr-1">
                <template x-for="item in items" :key="item.id">
                    <div class="flex gap-3 mb-4 pb-4 border-b border-black">
                        <img :src="item.image" class="w-16 h-16 object-cover rounded">
                        <div class="flex-1 relative">
                            <h4 class="font-bold text-sm" x-text="item.name"></h4>
                            <p class="text-xs text-[#8c6729] font-semibold text-gray-600 mb-1" x-text="'Rp ' + formatRupiah(item.price)"></p>
                            <div class="flex items-center gap-3">
                                <button @click="increment(item.id)" class="w-5 h-5 rounded-full border border-gray-400 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                                    <span class="material-icons-outlined !text-[14px]">add</span>
                                </button>
                                <span class="text-sm font-semibold" x-text="item.qty"></span>
                                <button @click="decrement(item.id)" class="w-5 h-5 rounded-full border border-gray-400 flex items-center justify-center text-gray-500 hover:bg-gray-200">
                                    <span class="material-icons-outlined !text-[14px]">remove</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="items.length === 0" class="text-center text-gray-500 mt-10 text-sm">Belum ada pesanan</div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="bg-[#f7f4e9] rounded-2xl p-5 border shadow-sm" style="border-color: #d1d5db;">
            <h3 class="text-lg font-bold font-serif mb-4">Pembayaran</h3>
            <div class="flex justify-between items-center mb-3">
                <span class="font-bold text-lg">TOTAL</span>
                <span class="font-bold text-lg" x-text="'Rp ' + formatRupiah(total)"></span>
            </div>
            <div class="border-t border-black mb-4"></div>
            
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium">Atas Nama</span>
                <span class="text-sm font-semibold bg-[#c5cb9f] px-3 py-1 rounded">Kalista</span>
            </div>
            <div class="flex justify-between items-center mb-5">
                <span class="text-sm font-medium">No Meja</span>
                <span class="text-sm font-semibold bg-[#c5cb9f] px-3 py-1 rounded">03</span>
            </div>

            <div class="flex justify-between items-center mb-3">
                <span class="font-bold">Bayar</span>
                <span class="font-bold bg-[#c5cb9f] px-3 py-1 rounded">Rp 50.000</span>
            </div>
            <div class="border-t border-black mb-3"></div>
            <div class="flex justify-between items-center mb-5">
                <span class="font-bold">Kembalian</span>
                <span class="font-bold">Rp 20.000</span>
            </div>
            <div class="border-t border-black mb-5"></div>

            <button class="w-full py-2.5 bg-[#c5cb9f] text-black font-bold rounded-lg hover:bg-[#b0b885] transition-colors" :disabled="items.length === 0" :class="{'opacity-50 cursor-not-allowed': items.length === 0}">
                Cetak Struk
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cart', () => ({
        items: [],
        orderType: 'Makan di Tempat',
        showOrderTypeDropdown: false,
        
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
@endpush
@endsection