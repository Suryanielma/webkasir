@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Tab Styling */
    .tab-btn.active {
        background-color: #d8dbbc;
        border-color: #d8dbbc;
    }
</style>

<div class="space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-400/40 pb-4">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-900">Laporan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manajemen pembukuan dan analisis laba rugi</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
            <form id="filterForm" method="GET" action="{{ route('laporan') }}" class="flex flex-col sm:flex-row gap-3 flex-1 md:flex-none">
                <input type="hidden" name="tab" id="activeTabInput" value="{{ request('tab', 'laba-rugi') }}">
                
                {{-- FIX DROPDOWN EXACT MATCH: Desain presisi merapat sesuai gambar image_582dbd.png --}}
                <div x-data="{ openFilter: false, selectedFilter: '{{ request('filter', 'mingguan') }}' }" class="relative inline-block flex-1 sm:flex-none">
                    <button type="button" @click="openFilter = !openFilter" @click.away="openFilter = false" 
                            class="w-full sm:w-auto bg-[#d8dbbc] text-[#333] px-4 py-2 rounded-full text-sm font-bold cursor-pointer shadow-sm outline-none inline-flex items-center justify-center gap-1 hover:bg-[#cbd0ae] transition-colors border-none">
                        <span x-text="selectedFilter === 'harian' ? 'Harian' : (selectedFilter === 'bulanan' ? 'Bulanan' : 'Mingguan')"></span>
                        <span class="material-icons-outlined !text-[16px] transition-transform duration-200 text-gray-700 pointer-events-none" :class="openFilter ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    
                    <input type="hidden" name="filter" :value="selectedFilter">
                    
                    <div x-show="openFilter" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 mt-1.5 w-full sm:w-36 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden font-semibold text-xs md:text-sm text-gray-700 divide-y divide-gray-50"
                         x-cloak>
                        <button type="button" @click="selectedFilter = 'harian'; openFilter = false; $nextTick(() => document.getElementById('filterForm').submit())" 
                                class="w-full text-left px-4 py-2.5 hover:bg-[#d8dbbc]/30 transition-colors" :class="selectedFilter === 'harian' ? 'bg-[#d8dbbc]/20 text-gray-950 font-bold' : ''">Harian</button>
                        <button type="button" @click="selectedFilter = 'mingguan'; openFilter = false; $nextTick(() => document.getElementById('filterForm').submit())" 
                                class="w-full text-left px-4 py-2.5 hover:bg-[#d8dbbc]/30 transition-colors" :class="selectedFilter === 'mingguan' ? 'bg-[#d8dbbc]/20 text-gray-950 font-bold' : ''">Mingguan</button>
                        <button type="button" @click="selectedFilter = 'bulanan'; openFilter = false; $nextTick(() => document.getElementById('filterForm').submit())" 
                                class="w-full text-left px-4 py-2.5 hover:bg-[#d8dbbc]/30 transition-colors" :class="selectedFilter === 'bulanan' ? 'bg-[#d8dbbc]/20 text-gray-950 font-bold' : ''">Bulanan</button>
                    </div>
                </div>
                
                <div class="relative flex-1 sm:flex-none">
                    <input type="text" name="date" id="datePicker" class="bg-[#d8dbbc] text-[#333] pl-4 pr-8 py-2 rounded-full text-sm font-bold cursor-pointer shadow-sm outline-none w-full sm:w-32 border-none text-center focus:ring-2 focus:ring-[#c5cb9f]" value="{{ request('date', date('d/m/Y')) }}" onchange="document.getElementById('filterForm').submit()" readonly>
                    <span class="material-icons-outlined text-sm absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-[#333]">arrow_drop_down</span>
                </div>
            </form>
            
            <a href="{{ route('laporan.export', ['filter' => request('filter', 'mingguan'), 'date' => request('date', date('d/m/Y'))]) }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full text-sm font-bold shadow-sm outline-none flex items-center justify-center gap-2 transition">
                <span class="material-icons-outlined text-sm">download</span> Excel
            </a>
        </div>
    </div>

    <div class="flex gap-2.5 overflow-x-auto pb-1 w-full sm:w-auto">
        <button class="tab-btn flex-1 sm:flex-none justify-center {{ request('tab', 'laba-rugi') == 'laba-rugi' ? 'active bg-[#d8dbbc]' : 'bg-white text-gray-700' }} px-5 py-2.5 rounded-xl border border-gray-300 font-bold text-sm flex items-center gap-2 shadow-sm transition whitespace-nowrap" onclick="switchTab('laba-rugi', this)">
            <span class="material-icons-outlined text-sm">analytics</span> Laba Rugi
        </button>
        <button class="tab-btn flex-1 sm:flex-none justify-center {{ request('tab') == 'rekapitulasi' ? 'active bg-[#d8dbbc]' : 'bg-white text-gray-700' }} px-5 py-2.5 rounded-xl border border-gray-300 font-bold text-sm flex items-center gap-2 shadow-sm transition whitespace-nowrap" onclick="switchTab('rekapitulasi', this)">
            <span class="material-icons-outlined text-sm">receipt_long</span> Rekapitulasi
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center h-44">
            <div class="w-10 h-10 bg-green-700 text-white rounded-full flex items-center justify-center mb-2 shadow-sm">
                <span class="material-icons-outlined !text-[20px]">payments</span>
            </div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pendapatan Usaha</h3>
            <p class="text-2xl font-bold text-gray-950 my-1">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            <span class="bg-green-700/10 text-green-700 text-[10px] font-bold px-4 py-1 rounded-lg w-full block">Total Penjualan</span>
        </div>

        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center h-44">
            <div class="w-10 h-10 bg-[#c62828] text-white rounded-full flex items-center justify-center mb-2 shadow-sm">
                <span class="material-icons-outlined !text-[20px]">restaurant_menu</span>
            </div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">HPP / Bahan Baku</h3>
            <p class="text-2xl font-bold text-gray-950 my-1">Rp {{ number_format($totalHpp, 0, ',', '.') }}</p>
            <span class="bg-red-50 text-[#c62828] text-[10px] font-bold px-4 py-1 rounded-lg w-full block">Total Pembelian</span>
        </div>

        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center h-44">
            <div class="w-10 h-10 bg-[#7cb342] text-white rounded-full flex items-center justify-center mb-2 shadow-sm">
                <span class="material-icons-outlined !text-[20px]">check</span>
            </div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laba Kotor</h3>
            <p class="text-2xl font-bold text-gray-950 my-1">Rp {{ number_format($labaKotor, 0, ',', '.') }}</p>
            <span class="bg-emerald-50 text-[#7cb342] text-[10px] font-bold px-4 py-1 rounded-lg w-full block">Total Laba</span>
        </div>

        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center h-44">
            <div class="w-10 h-10 bg-[#f57c00] text-white rounded-full flex items-center justify-center mb-2 shadow-sm">
                <span class="material-icons-outlined !text-[20px]">bar_chart</span>
            </div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Margin Kotor</h3>
            <p class="text-2xl font-bold text-gray-950 my-1">{{ $marginKotor }}%</p>
            <span class="bg-orange-50 text-[#f57c00] text-[10px] font-bold px-4 py-1 rounded-lg w-full block">Total Margin (%)</span>
        </div>
    </div>

    <div id="laba-rugi-content" class="tab-content {{ request('tab', 'laba-rugi') == 'laba-rugi' ? 'block' : 'hidden' }}">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Laporan Laba Rugi</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="border border-gray-300 rounded-2xl p-6 bg-[#eff1db]/70 shadow-sm flex flex-col min-h-[200px]">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">A. Pendapatan</h4>
                <div class="space-y-4 flex-grow">
                    <div class="flex justify-between items-center border-b border-gray-400/20 pb-2.5">
                        <span class="font-semibold text-sm text-gray-800">Pendapatan Total</span>
                        <span class="font-bold text-sm text-gray-950 text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-400/30 mt-auto">
                    <span class="font-bold text-sm text-gray-900">Total Pendapatan</span>
                    <span class="font-bold text-base text-green-700">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border border-gray-300 rounded-2xl p-6 bg-[#eff1db]/70 shadow-sm flex flex-col min-h-[200px]">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">B. HPP / Bahan Baku</h4>
                <div class="space-y-4 flex-grow">
                    <div class="flex justify-between items-center border-b border-gray-400/20 pb-2.5">
                        <span class="font-semibold text-sm text-gray-800">Bahan Baku Total</span>
                        <span class="font-bold text-sm text-gray-950 text-right">Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-400/30 mt-auto">
                    <span class="font-bold text-sm text-gray-900">Total HPP</span>
                    <span class="font-bold text-base text-red-600">Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border border-gray-300 rounded-2xl p-6 bg-[#eff1db]/70 shadow-sm flex flex-col min-h-[200px]">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-5">C. Formula Laba Kotor</h4>
                <div class="flex-grow">
                    <div class="flex flex-wrap items-center gap-1.5 mb-4">
                        <span class="px-2.5 py-0.5 border border-green-500 text-green-700 bg-white rounded-lg text-[11px] font-bold">Total Pendapatan</span>
                        <span class="font-bold text-gray-500 text-xs">-</span>
                        <span class="px-2.5 py-0.5 border border-red-400 text-red-600 bg-white rounded-lg text-[11px] font-bold">Total HPP</span>
                    </div>
                    <div class="flex flex-wrap justify-between items-center gap-2 border-b border-gray-400/20 pb-4">
                        <span class="font-bold text-sm text-gray-900">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                        <span class="font-bold text-gray-400 text-xs">-</span>
                        <span class="font-bold text-sm text-gray-900">Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-gray-400/30 mt-auto">
                    <span class="font-bold text-sm text-gray-900">Total Laba</span>
                    <span class="font-bold text-base text-green-700">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div id="rekapitulasi-content" class="tab-content {{ request('tab') == 'rekapitulasi' ? 'block' : 'hidden' }}">
        <div class="border border-gray-300 rounded-2xl overflow-hidden bg-white shadow-sm">
            <div class="p-4 border-b border-gray-200 bg-gray-50/60">
                <h3 class="font-bold text-gray-900 text-base">Rekapitulasi Penjualan</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[650px] md:min-w-0">
                    <thead class="bg-[#eff1db]/60 text-gray-800 font-bold border-b border-gray-300">
                        <tr>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5 text-right">Pendapatan</th>
                            <th class="px-6 py-3.5 text-right">HPP</th>
                            <th class="px-6 py-3.5 text-right">Laba Kotor</th>
                            <th class="px-6 py-3.5 text-right">Margin</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 divide-y divide-gray-100">
                        @forelse ($rekapitulasi as $rek)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-6 py-3.5 font-semibold text-gray-800">{{ $rek->tanggal }}</td>
                            <td class="px-6 py-3.5 text-right text-green-700 font-medium">Rp {{ number_format($rek->pendapatan, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5 text-right text-red-600 font-medium">Rp {{ number_format($rek->hpp, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5 text-right text-green-700 font-bold">Rp {{ number_format($rek->laba_kotor, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5 text-right font-bold text-gray-900">{{ $rek->margin }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">Tidak ada data untuk periode ini</td>
                        </tr>
                        @endforelse
                        
                        @if(count($rekapitulasi) > 0)
                        <tr class="bg-[#eff1db]/40 font-bold border-t-2 border-gray-400/60">
                            <td class="px-6 py-4 text-gray-900">Total<br><span class="text-xs font-normal text-gray-400">Periode Terpilih</span></td>
                            <td class="px-6 py-4 text-right text-green-700 text-base">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-red-600 text-base">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-green-700 text-base">Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-gray-950 text-base">{{ $marginKotor }}%</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    function switchTab(tabId, btnElement) {
        document.getElementById('activeTabInput').value = tabId;

        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active', 'bg-[#d8dbbc]');
            el.classList.add('bg-white', 'text-gray-700');
        });
        
        document.getElementById(tabId + '-content').classList.remove('hidden');
        document.getElementById(tabId + '-content').classList.add('block');
        
        btnElement.classList.add('active', 'bg-[#d8dbbc]');
        btnElement.classList.remove('bg-white', 'text-gray-700');
    }

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#datePicker", {
            dateFormat: "d/m/Y",
            allowInput: true
        });
    });
</script>
@endpush
@endsection