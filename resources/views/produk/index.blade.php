@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold tracking-tight">Menu</h2>
    <div class="flex gap-2 items-center">

        {{-- Dropdown Produk --}}
        <div class="relative">
            <button onclick="toggleDropdown('dropdown-produk')"
                class="flex items-center gap-1 bg-[#c5cb9f] text-[#5a4a2f] font-medium px-4 py-2 rounded-xl text-sm hover:bg-[#b5bb8f] transition">
                Produk <span class="material-icons-outlined !text-[16px]">expand_more</span>
            </button>
            <div id="dropdown-produk" class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg z-10 border border-gray-100">
                <a href="{{ route('produk.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">Daftar Produk</a>
                <a href="{{ route('produk.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-b-xl">Tambah Produk</a>
            </div>
        </div>

        {{-- Dropdown Kategori --}}
        <div class="relative">
            <button onclick="toggleDropdown('dropdown-kategori')"
                class="flex items-center gap-1 border border-gray-300 text-gray-700 font-medium px-4 py-2 rounded-xl text-sm hover:bg-gray-50 transition">
                Kategori <span class="material-icons-outlined !text-[16px]">expand_more</span>
            </button>
            <div id="dropdown-kategori" class="hidden absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg z-10 border border-gray-100">
                <a href="{{ route('menu') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">Daftar Kategori</a>
                <a href="{{ route('kategori.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-b-xl">Tambah Kategori</a>
            </div>
        </div>

        {{-- Tanggal --}}
        <div class="flex items-center gap-1 border border-gray-300 text-gray-700 font-medium px-4 py-2 rounded-xl text-sm">
            <span>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
            <span class="material-icons-outlined !text-[16px]">expand_more</span>
        </div>
    </div>
</div>

{{-- Filter & Search --}}
<div class="flex flex-wrap gap-3 items-center mb-6">
    <a href="{{ route('produk.create') }}"
        class="inline-flex items-center gap-2 bg-[#c5cb9f] text-[#5a4a2f] font-medium px-5 py-2.5 rounded-xl text-sm hover:bg-[#b5bb8f] transition">
        <span class="material-icons-outlined !text-[18px]">add</span>
        Tambah Produk
    </a>

    {{-- Filter Kategori --}}
    <div class="relative">
        <button onclick="toggleDropdown('filter-kategori')"
            class="flex items-center gap-1 border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
            {{ request('kategori') ? \App\Models\Kategori::find(request('kategori'))->nama_kategori : 'Semua Kategori' }}
            <span class="material-icons-outlined !text-[16px]">expand_more</span>
        </button>
        <div id="filter-kategori" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg z-10 border border-gray-100">
            <a href="{{ route('produk.index') }}"
                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">Semua Kategori</a>
            @foreach(\App\Models\Kategori::all() as $kat)
            <a href="{{ route('produk.index', ['kategori' => $kat->id_kategori]) }}"
                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 last:rounded-b-xl">
                {{ $kat->nama_kategori }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Filter Status --}}
    <div class="relative">
        <button onclick="toggleDropdown('filter-status')"
            class="flex items-center gap-1 border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
            {{ request('status') ? ucfirst(request('status')) : 'Semua Status' }}
            <span class="material-icons-outlined !text-[16px]">expand_more</span>
        </button>
        <div id="filter-status" class="hidden absolute left-0 mt-2 w-40 bg-white rounded-xl shadow-lg z-10 border border-gray-100">
            <a href="{{ route('produk.index', array_merge(request()->except('status'), [])) }}"
                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">Semua Status</a>
            <a href="{{ route('produk.index', array_merge(request()->all(), ['status' => 'tersedia'])) }}"
                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Tersedia</a>
            <a href="{{ route('produk.index', array_merge(request()->all(), ['status' => 'habis'])) }}"
                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-b-xl">Habis</a>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('produk.index') }}" class="flex-1">
        <div class="relative">
            <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 !text-[20px]">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama menu"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f]">
        </div>
    </form>
</div>

{{-- Tabel Produk --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 text-gray-500 text-left">
                <th class="px-6 py-4 font-medium w-8">#</th>
                <th class="px-6 py-4 font-medium">Nama Menu</th>
                <th class="px-6 py-4 font-medium">Kategori</th>
                <th class="px-6 py-4 font-medium">Harga</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($semua_produk as $i => $produk)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-400">{{ $i + 1 }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        {{-- Gambar produk --}}
                        @if($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}"
                                alt="{{ $produk->nama_produk }}"
                                class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-lg">🍽️</div>
                        @endif
                        <span class="font-medium text-gray-800">{{ $produk->nama_produk }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="text-base">
                            @php
                                $emojis = ['🍲','🥤','🍟','🍜','🍛','🧆'];
                                $idx = ($produk->id_kategori - 1) % count($emojis);
                                echo $emojis[$idx];
                            @endphp
                        </span>
                        <span class="text-gray-600">{{ $produk->kategori->nama_kategori ?? '-' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-700">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4">
    <button
        onclick="toggleStatus(this, {{ $produk->id_produk }})"
        data-status="{{ $produk->status }}"
        class="status-badge text-xs font-medium px-3 py-1 rounded-full border transition-all cursor-pointer
            {{ $produk->status === 'tersedia'
                ? 'bg-green-50 text-green-600 border-green-200 hover:bg-green-100'
                : 'bg-red-50 text-red-500 border-red-200 hover:bg-red-100' }}">
        {{ $produk->status === 'tersedia' ? 'Tersedia' : 'Habis' }}
    </button>
</td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('produk.edit', $produk->id_produk) }}"
                            class="p-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            <span class="material-icons-outlined !text-[16px] text-gray-600">edit</span>
                        </a>
                        <form action="{{ route('produk.destroy', $produk->id_produk) }}" method="POST"
                            onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                <span class="material-icons-outlined !text-[16px] text-red-400">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    Belum ada produk. Tambahkan produk pertama kamu!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
    function toggleDropdown(id) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"], [id^="filter-"]');
        allDropdowns.forEach(function(el) {
            if (el.id !== id) el.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="dropdown-"], [id^="filter-"]').forEach(function(el) {
                el.classList.add('hidden');
            });
        }
    });

    function toggleStatus(btn, id) {
    btn.disabled = true;
    btn.style.opacity = '0.5';

    fetch(`/produk/${id}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        const status = data.status;
        btn.dataset.status = status;
        btn.textContent = status === 'tersedia' ? 'Tersedia' : 'Habis';

        btn.className = 'status-badge text-xs font-medium px-3 py-1 rounded-full border transition-all cursor-pointer ' +
            (status === 'tersedia'
                ? 'bg-green-50 text-green-600 border-green-200 hover:bg-green-100'
                : 'bg-red-50 text-red-500 border-red-200 hover:bg-red-100');
    })
    .catch(() => alert('Gagal update status, coba lagi.'))
    .finally(() => {
        btn.disabled = false;
        btn.style.opacity = '1';
    });
}
</script>
@endpush