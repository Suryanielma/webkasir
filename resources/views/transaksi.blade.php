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
                <button @click="showInfoModal = true" 
                        class="px-3 py-1 rounded border border-gray-400 text-xs font-semibold hover:bg-gray-200 transition-colors flex items-center gap-1">
                    <span class="material-icons-outlined !text-[14px]">edit</span>
                    Edit Info
                </button>
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
                <span class="text-sm font-medium">Tipe Pesanan</span>
                <span class="text-sm font-semibold bg-[#c5cb9f] px-3 py-1 rounded" x-text="orderType"></span>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium">Atas Nama</span>
                <span class="text-sm font-semibold bg-[#c5cb9f] px-3 py-1 rounded" x-text="customerName || '-'"></span>
            </div>
            <div class="flex justify-between items-center mb-5" x-show="orderType === 'Makan di Tempat'">
                <span class="text-sm font-medium">No Meja</span>
                <span class="text-sm font-semibold bg-[#c5cb9f] px-3 py-1 rounded" x-text="tableNumber || '-'"></span>
            </div>

            <div class="border-t border-black mb-5"></div>

            <button @click="showPaymentModal = true" class="w-full py-2.5 bg-[#c5cb9f] text-black font-bold rounded-lg hover:bg-[#b0b885] transition-colors" :disabled="items.length === 0" :class="{'opacity-50 cursor-not-allowed': items.length === 0}">
                Bayar
            </button>
        </div>
    </div>

    <!-- Modal Edit Info -->
    <div x-show="showInfoModal" 
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm"
         style="display: none;">
        <div @click.away="showInfoModal = false" 
             class="bg-[#f7f4e9] rounded-2xl w-[400px] overflow-hidden shadow-xl border border-[#c5cb9f]"
             x-transition.scale.origin.bottom>
            <div class="p-5 border-b border-[#c5cb9f] flex justify-between items-center bg-[#e0e4c6]">
                <h3 class="font-bold font-serif text-xl text-black">Informasi Pesanan</h3>
                <button @click="showInfoModal = false" class="text-black hover:text-gray-700">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <div class="p-6 flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-black mb-1">Tipe Pesanan</label>
                    <select x-model="orderType" class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-1 focus:ring-[#6a4f21] bg-white text-black">
                        <option value="Makan di Tempat">Makan di Tempat</option>
                        <option value="Bawa Pulang">Bawa Pulang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-black mb-1">Atas Nama</label>
                    <input type="text" x-model="customerName" class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-1 focus:ring-[#6a4f21] bg-white text-black">
                </div>
                <div x-show="orderType === 'Makan di Tempat'" x-transition>
                    <label class="block text-sm font-medium text-black mb-1">No Meja</label>
                    <input type="number" x-model="tableNumber" class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-1 focus:ring-[#6a4f21] bg-white text-black">
                </div>
            </div>
            <div class="p-4 border-t border-[#c5cb9f] flex justify-end bg-transparent">
                <button @click="showInfoModal = false" class="px-6 py-2 bg-[#c5cb9f] hover:bg-[#b0b885] text-black font-bold rounded transition-colors shadow-sm">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div x-show="showPaymentModal" 
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm"
         style="display: none;">
        <div @click.away="showPaymentModal = false" 
             class="bg-[#f7f4e9] rounded-2xl w-[400px] overflow-hidden shadow-xl border border-[#c5cb9f]"
             x-transition.scale.origin.bottom>
            <div class="p-5 border-b border-[#c5cb9f] flex justify-between items-center bg-[#e0e4c6]">
                <h3 class="font-bold font-serif text-xl text-black">Pembayaran</h3>
                <button @click="showPaymentModal = false" class="text-black hover:text-gray-700">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('transaksi.checkout') }}" method="POST">
                @csrf
                <div class="p-6 flex flex-col gap-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium">Total Tagihan:</span>
                        <span class="text-lg font-bold" x-text="'Rp ' + formatRupiah(total)"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Jumlah Bayar</label>
                        <input type="number" x-model.number="payAmount" min="0" class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-1 focus:ring-[#6a4f21] bg-white text-black" required>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm font-medium">Kembalian:</span>
                        <span class="text-lg font-bold" :class="payAmount >= total ? 'text-green-600' : 'text-red-600'" x-text="'Rp ' + formatRupiah(Math.max(0, payAmount - total))"></span>
                    </div>
                </div>
                
                <input type="hidden" name="total_harga" :value="total">
                <input type="hidden" name="bayar" :value="payAmount">
                <input type="hidden" name="kembalian" :value="Math.max(0, payAmount - total)">
                <input type="hidden" name="tipe_pesanan" :value="orderType">
                <input type="hidden" name="nama_pembeli" :value="customerName">
                <input type="hidden" name="nomor_meja" :value="tableNumber">
                <input type="hidden" name="items" :value="JSON.stringify(items.map(i => ({ id_produk: i.id, qty: i.qty, harga: i.price })))">

                <div class="p-4 border-t border-[#c5cb9f] flex justify-end gap-2 bg-transparent">
                    <button type="button" @click="showPaymentModal = false" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-black font-bold rounded transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2 bg-[#c5cb9f] hover:bg-[#b0b885] text-black font-bold rounded transition-colors shadow-sm" :disabled="payAmount < total" :class="{'opacity-50 cursor-not-allowed': payAmount < total}">
                        Proses Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cart', () => ({
        items: [],
        orderType: 'Makan di Tempat',
        customerName: '',
        tableNumber: '',
        showInfoModal: false,
        showPaymentModal: false,
        payAmount: 0,
        
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
        // Buka popup untuk print struk
        const url = "{{ route('transaksi.struk', session('cetak_struk')) }}";
        window.open(url, "StrukPembayaran", "width=400,height=600,left=200,top=100");
    });
</script>
@endif
@endpush
@endsection