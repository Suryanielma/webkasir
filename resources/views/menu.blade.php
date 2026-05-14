@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold tracking-tight">Menu</h2>
    <div class="flex gap-2 items-center">

        {{-- Tab Produk --}}
        <button onclick="switchTab('produk')" id="btn-produk"
            class="flex items-center gap-1 font-medium px-4 py-2 rounded-xl text-sm transition bg-[#c5cb9f] text-[#5a4a2f]">
            Produk <span class="material-icons-outlined !text-[16px]">expand_more</span>
        </button>

        {{-- Tab Kategori --}}
        <button onclick="switchTab('kategori')" id="btn-kategori"
            class="flex items-center gap-1 font-medium px-4 py-2 rounded-xl text-sm transition border border-gray-300 text-gray-700 hover:bg-gray-50">
            Kategori <span class="material-icons-outlined !text-[16px]">expand_more</span>
        </button>

        {{-- Tanggal --}}
        <div class="flex items-center gap-1 border border-gray-300 text-gray-700 font-medium px-4 py-2 rounded-xl text-sm">
            <span>{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
            <!-- <span class="material-icons-outlined !text-[16px]">expand_more</span> -->
        </div>
    </div>
</div>

{{-- ===================== TAB PRODUK ===================== --}}
<div id="tab-produk">
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
                <a href="{{ route('menu', ['tab' => 'produk']) }}"
                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">Semua Kategori</a>
                @foreach(\App\Models\Kategori::all() as $kat)
                <a href="{{ route('menu', ['tab' => 'produk', 'kategori' => $kat->id_kategori]) }}"
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
                <a href="{{ route('menu', array_merge(request()->except('status'), ['tab' => 'produk'])) }}"
                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">Semua Status</a>
                <a href="{{ route('menu', array_merge(request()->all(), ['tab' => 'produk', 'status' => 'tersedia'])) }}"
                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Tersedia</a>
                <a href="{{ route('menu', array_merge(request()->all(), ['tab' => 'produk', 'status' => 'habis'])) }}"
                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-b-xl">Habis</a>
            </div>
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('menu') }}" class="flex-1">
            <input type="hidden" name="tab" value="produk">
            @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
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
                            @php
                                $emojis = ['🍲','🥤','🍟','🍜','🍛','🧆'];
                                echo $emojis[($produk->id_kategori - 1) % count($emojis)];
                            @endphp
                            <span class="text-gray-600 ml-1">{{ $produk->kategori->nama_kategori ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if(($produk->status ?? 'tersedia') === 'tersedia')
                            <span class="bg-green-50 text-green-600 text-xs font-medium px-3 py-1 rounded-full border border-green-200">Tersedia</span>
                        @else
                            <span class="bg-red-50 text-red-500 text-xs font-medium px-3 py-1 rounded-full border border-red-200">Habis</span>
                        @endif
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
                        Belum ada produk. Tambahkan produk pertama!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===================== TAB KATEGORI ===================== --}}
<div id="tab-kategori" class="hidden">
    <div class="mb-6">
        <a href="{{ route('kategori.create') }}"
            class="inline-flex items-center gap-2 bg-[#c5cb9f] text-[#5a4a2f] font-medium px-5 py-2.5 rounded-xl text-sm hover:bg-[#b5bb8f] transition">
            <span class="material-icons-outlined !text-[18px]">add</span>
            Tambah Kategori
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @php $emojis = ['🍲','🥤','🍟','🍜','🍛','🧆','🥗','🍱']; @endphp

        @forelse($kategoris as $index => $kategori)
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-start gap-4 relative">
            <div class="absolute top-3 right-3 flex gap-1">
                <a href="{{ route('kategori.edit', $kategori->id_kategori) }}"
                    class="p-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <span class="material-icons-outlined !text-[16px] text-gray-600">edit</span>
                </a>
                <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST"
                    onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="p-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">
                        <span class="material-icons-outlined !text-[16px] text-red-400">delete</span>
                    </button>
                </form>
            </div>
            <div class="text-4xl mt-1">{{ $emojis[$index % count($emojis)] }}</div>
            <div class="pr-12">
                <h3 class="font-semibold text-gray-800">{{ $kategori->nama_kategori }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center text-gray-400 py-12">
            Belum ada kategori. Tambahkan kategori pertama!
        </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        document.getElementById('tab-produk').classList.add('hidden');
        document.getElementById('tab-kategori').classList.add('hidden');
        document.getElementById('tab-' + tab).classList.remove('hidden');

        const btnProduk   = document.getElementById('btn-produk');
        const btnKategori = document.getElementById('btn-kategori');
        const activeClass = 'flex items-center gap-1 font-medium px-4 py-2 rounded-xl text-sm transition bg-[#c5cb9f] text-[#5a4a2f]';
        const inactiveClass = 'flex items-center gap-1 font-medium px-4 py-2 rounded-xl text-sm transition border border-gray-300 text-gray-700 hover:bg-gray-50';

        if (tab === 'produk') {
            btnProduk.className   = activeClass;
            btnKategori.className = inactiveClass;
        } else {
            btnKategori.className = activeClass;
            btnProduk.className   = inactiveClass;
        }
    }

    // Buka tab sesuai ?tab= di URL
    const activeTab = new URLSearchParams(window.location.search).get('tab') || 'produk';
    switchTab(activeTab);

    // Dropdown filter
    function toggleDropdown(id) {
        document.querySelectorAll('[id^="filter-"]').forEach(function(el) {
            if (el.id !== id) el.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="filter-"]').forEach(function(el) {
                el.classList.add('hidden');
            });
        }
    });
</script>
@endpush