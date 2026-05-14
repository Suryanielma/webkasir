@extends('layouts.app')

@section('content')
<div class="max-w-2xl">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('menu', ['tab' => 'kategori']) }}"
            class="p-2 hover:bg-gray-100 rounded-xl transition">
            <span class="material-icons-outlined text-gray-500">arrow_back</span>
        </a>
        <h2 class="text-2xl font-bold tracking-tight">Edit Kategori</h2>
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

    <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST"
        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Nama Kategori --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                Nama Kategori <span class="text-red-400">*</span>
            </label>
            <input type="text" name="nama_kategori"
                value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                placeholder="cth: Makanan, Minuman, Pendamping"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] @error('nama_kategori') border-red-400 @enderror">
            @error('nama_kategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
            </label>
            <textarea name="deskripsi" rows="2"
                placeholder="cth: Menu makanan berat"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#c5cb9f] resize-none">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3 pt-2">
            <a href="{{ route('menu', ['tab' => 'kategori']) }}"
                class="flex-1 text-center px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="flex-1 bg-[#c5cb9f] text-[#5a4a2f] font-medium px-4 py-2.5 rounded-xl text-sm hover:bg-[#b5bb8f] transition">
                Update Kategori
            </button>
        </div>
    </form>
</div>
@endsection