@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Edit Bahan Baku</h2>
    
    <form action="{{ route('bahan-baku.update', $bahanBaku) }}" method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')
        
        {{-- Form fields akan ditambahkan sesuai kebutuhan --}}
        
        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('bahan-baku.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>

@endsection
