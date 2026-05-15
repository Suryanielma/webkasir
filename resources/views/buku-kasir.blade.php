@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px]">
    <!-- Header -->
    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-4xl font-bold font-serif text-black mb-1">Buku Kasir</h2>
            <p class="text-sm font-bold text-gray-500 font-serif">Rabu, 14 Mei 2026</p>
        </div>
        <div class="flex gap-2.5 relative top-2">
            <button class="px-4 py-1.5 bg-[#c5cb9f] text-black font-semibold rounded border border-gray-400 text-sm hover:bg-[#b8be92] transition-colors">Hari Ini</button>
            <button class="px-4 py-1.5 bg-[#f7f4e9] text-black font-semibold rounded border border-gray-400 text-sm hover:bg-gray-100 transition-colors">Kemarin</button>
        </div>
    </div>
    
    <div class="border-t border-black mb-6"></div>

    <!-- Cards Summary -->
    <div class="flex gap-6 mb-8">
        <!-- Card Total Transaksi -->
        <div class="bg-white rounded-lg border border-gray-400 p-5 w-[200px] flex flex-col justify-between shadow-sm">
            <h4 class="font-bold text-sm text-black mb-4">Total Transaksi</h4>
            <p class="text-4xl font-bold font-serif text-black mb-6">34</p>
            <span class="bg-[#788e5e] text-white text-xs font-semibold text-center px-4 py-1.5 rounded-full w-full block">Selesai</span>
        </div>
        <!-- Card Total Pendapatan -->
        <div class="bg-white rounded-lg border border-gray-400 p-5 w-[240px] flex flex-col justify-between shadow-sm">
            <h4 class="font-bold text-sm text-black mb-4">Total Pendapatan</h4>
            <p class="text-3xl font-bold font-serif text-black mb-6">Rp 500.000</p>
            <span class="bg-[#4e825a] text-white text-xs font-semibold text-center px-4 py-1.5 rounded-full w-full block">Rupiah (IDR)</span>
        </div>
    </div>

    <!-- Table Container -->
    <div class="border border-black rounded-lg overflow-hidden flex flex-col bg-[#f7f4e9]">
        <!-- Table Header -->
        <div class="bg-[#c5cb9f] grid grid-cols-5 px-6 py-4 border-b border-black">
            <div class="font-bold text-black font-serif text-sm">No Struk</div>
            <div class="font-bold text-black font-serif text-sm">Waktu</div>
            <div class="font-bold text-black font-serif text-sm">Pelanggan</div>
            <div class="font-bold text-black font-serif text-sm">Total</div>
            <div class="font-bold text-black font-serif text-sm">Status</div>
        </div>

        <!-- Table Row 1 -->
        <div class="grid grid-cols-5 px-6 py-4 border-b border-black items-center">
            <div class="font-bold text-black text-sm font-serif">Es Teh</div>
            <div class="font-bold text-black text-sm font-serif">06.30</div>
            <div class="font-bold text-black text-sm font-serif">Kalista</div>
            <div class="font-bold text-black text-sm font-serif">Rp. 50.000,00</div>
            <div>
                <span class="px-5 py-1 rounded-full border border-green-600 text-green-700 text-xs font-bold text-center inline-block w-[100px]">Selesai</span>
            </div>
        </div>
        
        <!-- Table Row 2 -->
        <div class="grid grid-cols-5 px-6 py-4 border-b border-black items-center">
            <div class="font-bold text-black text-sm font-serif">Soto Ayam</div>
            <div class="font-bold text-black text-sm font-serif">07.00</div>
            <div class="font-bold text-black text-sm font-serif">Budi</div>
            <div class="font-bold text-black text-sm font-serif">Rp. 65.000,00</div>
            <div>
                <span class="px-5 py-1 rounded-full border border-green-600 text-green-700 text-xs font-bold text-center inline-block w-[100px]">Selesai</span>
            </div>
        </div>

        <!-- Table Row 3 -->
        <div class="grid grid-cols-5 px-6 py-4 border-b border-black items-center">
            <div class="font-bold text-black text-sm font-serif">Soto Daging</div>
            <div class="font-bold text-black text-sm font-serif">07.15</div>
            <div class="font-bold text-black text-sm font-serif">Cahya</div>
            <div class="font-bold text-black text-sm font-serif">Rp. 40.000,00</div>
            <div>
                <span class="px-5 py-1 rounded-full border border-green-600 text-green-700 text-xs font-bold text-center inline-block w-[100px]">Selesai</span>
            </div>
        </div>

        <!-- Table Row 4 -->
        <div class="grid grid-cols-5 px-6 py-4 border-b border-black items-center">
            <div class="font-bold text-black text-sm font-serif">Es Jeruk</div>
            <div class="font-bold text-black text-sm font-serif">07.16</div>
            <div class="font-bold text-black text-sm font-serif">Kirts</div>
            <div class="font-bold text-black text-sm font-serif">Rp. 25.000,00</div>
            <div>
                <span class="px-5 py-1 rounded-full border border-red-500 text-red-500 text-xs font-bold text-center inline-block w-[100px]">Habis</span>
            </div>
        </div>

        <!-- Log Header -->
        <div class="bg-[#c5cb9f] px-6 py-3 border-b border-black">
            <div class="font-bold text-black font-serif text-sm">Log buka/ Tutup Kasir</div>
        </div>

        <!-- Log Row 1 -->
        <div class="flex px-6 py-4 border-b border-black items-center gap-16">
            <div class="font-bold text-black text-sm font-serif w-12">06.00</div>
            <div class="flex items-center gap-3 w-40">
                <div class="w-2.5 h-2.5 bg-[#4CAF50] rounded-full"></div>
                <div class="font-bold text-black text-sm font-serif">Kasir dibuka</div>
            </div>
            <div class="font-bold text-black text-sm font-serif">Saldo Awal Rp. 200.000,00</div>
        </div>

        <!-- Log Row 2 -->
        <div class="flex px-6 py-4 border-b border-black items-center gap-16">
            <div class="font-bold text-black text-sm font-serif w-12">06.00</div>
            <div class="flex items-center gap-3 w-40">
                <div class="w-2.5 h-2.5 bg-[#F44336] rounded-full"></div>
                <div class="font-bold text-black text-sm font-serif">Kasir ditutup</div>
            </div>
            <div class="font-bold text-black text-sm font-serif">Saldo Akhir Rp. 1.000.000,00</div>
        </div>

        <!-- Table Footer -->
        <div class="bg-[#c5cb9f] py-2 flex justify-center text-center cursor-pointer hover:bg-[#b8be92] transition-colors">
            <span class="font-bold text-black font-serif text-[13px] flex items-center justify-center">
                Lihat Semua (34) <span class="material-icons-outlined !text-[18px] ml-1">arrow_drop_down</span>
            </span>
        </div>
    </div>
</div>
@endsection