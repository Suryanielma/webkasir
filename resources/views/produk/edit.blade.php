@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('produk.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition">
            <span class="material-icons-outlined text-gray-500">arrow_back</span>
        </a>
        <h2 class="text-2xl font-bold tracking-tight">Edit Produk</h2>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl p-4 mb-6 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex gap-6 items-start">

        {{-- Form Kiri --}}
        <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data"
            class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Produk --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}"
                    placeholder="cth: Soto Ayam"
                    oninput="document.getElementById('preview-nama').textContent = this.value || 'Nama Produk'"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] @error('nama_produk') border-red-400 @enderror">
                @error('nama_produk')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                <select name="id_kategori"
                    onchange="document.getElementById('preview-kategori').textContent = this.options[this.selectedIndex].text"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white @error('id_kategori') border-red-400 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id_kategori }}"
                            {{ old('id_kategori', $produk->id_kategori) == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                    <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}"
                        placeholder="10000"
                        oninput="document.getElementById('preview-harga').textContent = this.value ? 'Rp ' + Number(this.value).toLocaleString('id-ID') : 'Rp 0'"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] @error('harga') border-red-400 @enderror">
                </div>
                @error('harga')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status"
                    onchange="updateStatusPreview(this.value)"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white">
                    <option value="tersedia" {{ old('status', $produk->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="habis" {{ old('status', $produk->status) == 'habis' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>

            {{-- Gambar --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Gambar Produk</label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#c5cb9f] transition cursor-pointer"
                    onclick="document.getElementById('gambar').click()">
                    <div id="preview-container-form" class="{{ $produk->gambar ? '' : 'hidden' }} mb-3">
                        <img id="preview-img-form"
                            src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : '' }}"
                            alt="Preview" class="w-24 h-24 object-cover rounded-xl mx-auto">
                    </div>
                    <span class="material-icons-outlined text-gray-300 !text-[40px] {{ $produk->gambar ? 'hidden' : '' }}" id="upload-icon">add_photo_alternate</span>
                    <p class="text-sm text-gray-400 mt-2" id="upload-text">
                        {{ $produk->gambar ? 'Klik untuk ganti gambar' : 'Klik untuk upload gambar' }}
                    </p>
                    <p class="text-xs text-gray-300 mt-1">PNG, JPG, JPEG — maks 2MB</p>
                    <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden"
                        onchange="previewGambar(event)">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('produk.index') }}"
                    class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-[#c5cb9f] text-[#5a4a2f] font-medium px-4 py-2.5 rounded-xl text-sm hover:bg-[#b5bb8f] transition">
                    Update Produk
                </button>
            </div>
        </form>

        {{-- Preview Kanan --}}
        <div class="w-72 sticky top-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-400 mb-4 font-medium uppercase tracking-wide">Preview Produk</p>

                {{-- Gambar Preview --}}
                <div class="w-full h-40 bg-[#f7f4e9] rounded-xl flex items-center justify-center mb-4 overflow-hidden">
                    <span class="material-icons-outlined text-gray-300 !text-[48px] {{ $produk->gambar ? 'hidden' : '' }}" id="preview-placeholder">fastfood</span>
                    <img id="preview-img-card"
                        src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : '' }}"
                        alt=""
                        class="{{ $produk->gambar ? '' : 'hidden' }} w-full h-full object-cover rounded-xl">
                </div>

                {{-- Info --}}
                <h3 id="preview-nama" class="font-semibold text-gray-800 text-base">
                    {{ $produk->nama_produk }}
                </h3>
                <p id="preview-kategori" class="text-xs text-gray-400 mt-0.5 mb-3">
                    {{ $produk->kategori->nama_kategori ?? 'Kategori' }}
                </p>

                <div class="flex items-center justify-between">
                    <p id="preview-harga" class="text-[#5a4a2f] font-semibold text-sm">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </p>
                    <span id="preview-status"
                        class="{{ $produk->status === 'tersedia' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-500 border-red-200' }} text-xs font-medium px-3 py-1 rounded-full border">
                        {{ ucfirst($produk->status) }}
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewGambar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img-form').src = e.target.result;
                document.getElementById('preview-container-form').classList.remove('hidden');
                document.getElementById('upload-icon').classList.add('hidden');
                document.getElementById('upload-text').textContent = file.name;

                document.getElementById('preview-img-card').src = e.target.result;
                document.getElementById('preview-img-card').classList.remove('hidden');
                document.getElementById('preview-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function updateStatusPreview(val) {
        const el = document.getElementById('preview-status');
        if (val === 'tersedia') {
            el.textContent = 'Tersedia';
            el.className = 'bg-green-50 text-green-600 text-xs font-medium px-3 py-1 rounded-full border border-green-200';
        } else {
            el.textContent = 'Habis';
            el.className = 'bg-red-50 text-red-500 text-xs font-medium px-3 py-1 rounded-full border border-red-200';
        }
    }
</script>
@endpush