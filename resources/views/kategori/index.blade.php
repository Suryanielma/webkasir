@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold tracking-tight">Kategori</h2>
    <a href="{{ route('menu', ['tab' => 'kategori']) }}"
        class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition">
        <span class="material-icons-outlined !text-[18px]">arrow_back</span>
        Kembali ke Menu
    </a>
</div>

<div class="mb-6">
    <a href="{{ route('kategori.create') }}"
        class="inline-flex items-center gap-2 bg-[#c5cb9f] text-[#5a4a2f] font-medium px-5 py-2.5 rounded-xl text-sm hover:bg-[#b5bb8f] transition">
        <span class="material-icons-outlined !text-[18px]">add</span>
        Tambah Kategori
    </a>
</div>

{{-- Grid Kategori --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @php $emojis = ['🍲','🥤','🍟','🍜','🍛','🧆','🥗','🍱']; @endphp

    @forelse($kategori as $index => $k)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100" style="display:flex; flex-direction:column;">

        {{-- Emoji + Info --}}
        <div style="display:flex; align-items:flex-start; gap:16px; padding:20px;">
            <div style="font-size:2.25rem; margin-top:2px;">{{ $emojis[$index % count($emojis)] }}</div>
            <div>
                <h3 style="font-weight:600; color:#1f2937; margin:0;">{{ $k->nama_kategori }}</h3>
                <p style="font-size:12px; color:#9ca3af; margin:4px 0 0;">
                    {{ $k->deskripsi ?? 'Tidak ada deskripsi' }}
                </p>
            </div>
        </div>

        {{-- Tombol Edit & Hapus di bawah --}}
        <div style="display:flex; gap:8px; padding:0 16px 16px; border-top:1px solid #f3f4f6; padding-top:12px;">
            <a href="{{ route('kategori.edit', $k->id_kategori) }}"
                style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; font-size:12px; color:#4b5563; border:1px solid #e5e7eb; border-radius:10px; padding:7px; text-decoration:none;">
                <span class="material-icons-outlined" style="font-size:15px;">edit</span>
                Edit
            </a>
            <form action="{{ route('kategori.destroy', $k->id_kategori) }}" method="POST"
                onsubmit="return confirm('Hapus kategori ini?')" style="flex:1;">
                @csrf @method('DELETE')
                <button type="submit"
                    style="width:100%; display:flex; align-items:center; justify-content:center; gap:6px; font-size:12px; color:#f87171; border:1px solid #fee2e2; border-radius:10px; padding:7px; background:transparent; cursor:pointer;">
                    <span class="material-icons-outlined" style="font-size:15px;">delete</span>
                    Hapus
                </button>
            </form>
        </div>

    </div>
    @empty
    <div class="col-span-4 text-center text-gray-400 py-12">
        Belum ada kategori. Tambahkan kategori pertama!
    </div>
    @endforelse
</div>
@endsection