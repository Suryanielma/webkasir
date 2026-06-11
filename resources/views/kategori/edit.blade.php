@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto w-full">
    {{-- Header: Ukuran teks adaptif & tombol kembali yang dipercantik --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('menu', ['tab' => 'kategori']) }}"
            class="p-2 hover:bg-gray-100 rounded-xl transition flex items-center justify-center border border-gray-200 bg-white shadow-sm">
            <span class="material-icons-outlined text-gray-500 !text-[20px]">arrow_back</span>
        </a>
        <h2 class="text-xl md:text-2xl font-bold tracking-tight text-gray-900">Edit Kategori</h2>
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

    {{-- Form Container --}}
    <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST"
        class="bg-white rounded-2xl shadow-sm border border-gray-200/70 p-5 sm:p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Nama Kategori --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_kategori"
                value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                placeholder="cth: Makanan, Minuman, Pendamping"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] text-gray-800 bg-white @error('nama_kategori') border-red-400 focus:ring-red-200 @enderror" required>
            @error('nama_kategori')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                Deskripsi <span class="text-gray-400 font-normal lowercase">(opsional)</span>
            </label>
            <textarea name="deskripsi" rows="3"
                placeholder="cth: Menu makanan berat"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] text-gray-800 bg-white resize-none">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
        </div>

        {{-- Tombol Aksi: Fleksibel Menumpuk di HP, Sejajar di Desktop --}}
        <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
            <a href="{{ route('menu', ['tab' => 'kategori']) }}"
                class="w-full sm:flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition bg-white shadow-sm">
                Batal
            </a>
            <button type="submit"
                class="w-full sm:flex-1 bg-[#c5cb9f] text-[#5a4a2f] font-bold px-4 py-2.5 rounded-xl text-sm hover:bg-[#b5bb8f] transition shadow-sm cursor-pointer">
                Update Kategori
            </button>
        </div>
    </form>
</div>
@endsection