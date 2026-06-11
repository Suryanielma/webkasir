@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto w-full">
    {{-- Header: Ukuran teks adaptif & tombol kembali disesuaikan ke tab produk --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('menu', ['tab' => 'produk']) }}" 
            class="p-2 hover:bg-gray-100 rounded-xl transition flex items-center justify-center border border-gray-200 bg-white shadow-sm">
            <span class="material-icons-outlined text-gray-500 !text-[20px]">arrow_back</span>
        </a>
        <h2 class="text-xl md:text-2xl font-bold tracking-tight text-gray-900">Tambah Produk</h2>
    </div>

    {{-- Error Validation Alert --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl p-4 mb-6 text-sm shadow-sm">
        <ul class="list-disc list-inside space-y-1 font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6 items-start w-full">

        {{-- Form Bagian Kiri --}}
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data"
            class="w-full flex-1 bg-white rounded-2xl shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-5 order-2 lg:order-1">
            @csrf

            {{-- Nama Produk --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}"
                    placeholder="cth: Soto Ayam Kambing"
                    oninput="document.getElementById('preview-nama').textContent = this.value || 'Nama Produk'"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] text-gray-800 bg-white @error('nama_produk') border-red-400 focus:ring-red-200 @enderror" required>
                @error('nama_produk')
                    <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select name="id_kategori"
                    onchange="document.getElementById('preview-kategori').textContent = this.options[this.selectedIndex].text"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white text-gray-800 @error('id_kategori') border-red-400 focus:ring-red-200 @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori')
                    <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Harga <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">Rp</span>
                    <input type="number" name="harga" value="{{ old('harga') }}"
                        placeholder="10000"
                        oninput="document.getElementById('preview-harga').textContent = this.value ? 'Rp ' + Number(this.value).toLocaleString('id-ID') : 'Rp 0'"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] text-gray-900 font-medium bg-white @error('harga') border-red-400 focus:ring-red-200 @enderror" required>
                </div>
                @error('harga')
                    <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status"
                    onchange="updateStatusPreview(this.value)"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] bg-white text-gray-800 font-medium">
                    <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>

            {{-- Gambar Upload Area --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Gambar Produk</label>
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-[#c5cb9f] transition cursor-pointer bg-gray-50/50"
                    onclick="document.getElementById('gambar').click()">
                    <div id="preview-container-form" class="hidden mb-3">
                        <img id="preview-img-form" src="" alt="Preview"
                            class="w-24 h-24 object-cover rounded-xl mx-auto shadow-sm">
                    </div>
                    <span class="material-icons-outlined text-gray-300 !text-[36px]" id="upload-icon">add_photo_alternate</span>
                    <p class="text-sm font-semibold text-gray-500 mt-1" id="upload-text">Klik untuk upload gambar</p>
                    <p class="text-xs text-gray-400 mt-0.5">PNG, JPG, JPEG — maks 2MB</p>
                    <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden"
                        onchange="previewGambar(event)">
                </div>
            </div>

            {{-- Tombol Aksi Form --}}
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                <a href="{{ route('menu', ['tab' => 'produk']) }}"
                    class="w-full sm:flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition bg-white shadow-sm">
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:flex-1 bg-[#c5cb9f] text-[#5a4a2f] font-bold px-4 py-2.5 rounded-xl text-sm hover:bg-[#b5bb8f] transition shadow-sm cursor-pointer">
                    Simpan Produk
                </button>
            </div>
        </form>

        {{-- Preview Bagian Kanan / Bawah (Responsif: Lebar penuh di HP, w-72 sticky di desktop) --}}
        <div class="w-full lg:w-72 static lg:sticky lg:top-6 order-1 lg:order-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5">
                <p class="text-xs text-gray-400 mb-4 font-bold uppercase tracking-wider">Live Preview Card</p>

                {{-- Gambar Preview Kotak --}}
                <div id="preview-img-wrapper"
                    class="w-full h-40 bg-[#f7f4e9] rounded-xl flex items-center justify-center mb-4 overflow-hidden border border-gray-100 shadow-inner">
                    <span class="material-icons-outlined text-gray-300 !text-[44px]" id="preview-placeholder">fastfood</span>
                    <img id="preview-img-card" src="" alt="" class="hidden w-full h-full object-cover rounded-xl">
                </div>

                {{-- Informasi Menu Live --}}
                <h3 id="preview-nama" class="font-bold text-gray-950 text-base truncate">Nama Produk</h3>
                <p id="preview-kategori" class="text-xs font-semibold text-gray-400 mt-0.5 mb-3">Kategori</p>

                <div class="flex items-center justify-between gap-2 border-t border-gray-50 pt-3">
                    <p id="preview-harga" class="text-[#5a4a2f] font-extrabold text-sm">Rp 0</p>
                    <span id="preview-status"
                        class="bg-green-50 text-green-600 text-[11px] font-bold px-3 py-1 rounded-full border border-green-200 shadow-sm whitespace-nowrap">
                        Tersedia
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
                // Update form preview kecil
                document.getElementById('preview-img-form').src = e.target.result;
                document.getElementById('preview-container-form').classList.remove('hidden');
                document.getElementById('upload-icon').classList.add('hidden');
                document.getElementById('upload-text').textContent = file.name;

                // Update card preview kanan
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
            el.className = 'bg-green-50 text-green-600 text-[11px] font-bold px-3 py-1 rounded-full border border-green-200 shadow-sm whitespace-nowrap';
        } else {
            el.textContent = 'Habis';
            el.className = 'bg-red-50 text-red-500 text-[11px] font-bold px-3 py-1 rounded-full border border-red-200 shadow-sm whitespace-nowrap';
        }
    }
</script>
@endpush