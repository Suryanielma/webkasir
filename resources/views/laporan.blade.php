@extends('layouts.app')

@section('content')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Tab Styling */
    .tab-btn.active {
        background-color: #d8dbbc;
        border-color: #d8dbbc;
    }
</style>

<div class="flex justify-between items-center mb-8 border-b border-gray-400/40 pb-4">
    <h2 class="text-3xl font-bold tracking-tight text-gray-900 font-serif">Laporan</h2>
    <div class="flex gap-4">
        <form id="filterForm" method="GET" action="{{ route('laporan') }}" class="flex gap-4">
            <input type="hidden" name="tab" id="activeTabInput" value="{{ request('tab', 'laba-rugi') }}">
            
            <div class="relative">
                <select name="filter" onchange="document.getElementById('filterForm').submit()" class="appearance-none bg-[#d8dbbc] text-[#333] pl-4 pr-10 py-2 rounded-full text-sm font-semibold cursor-pointer shadow-sm outline-none border-none">
                    <option value="harian" {{ request('filter') == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="mingguan" {{ request('filter', 'mingguan') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="bulanan" {{ request('filter') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
                <span class="material-icons-outlined text-sm absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-[#333]">arrow_drop_down</span>
            </div>
            
            <div class="relative">
                <input type="text" name="date" id="datePicker" class="bg-[#d8dbbc] text-[#333] pl-4 pr-8 py-2 rounded-full text-sm font-semibold cursor-pointer shadow-sm outline-none w-32 border-none text-center" value="{{ request('date', date('d/m/Y')) }}" onchange="document.getElementById('filterForm').submit()" readonly>
                <span class="material-icons-outlined text-sm absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-[#333]">arrow_drop_down</span>
            </div>
        </form>
        <a href="{{ route('laporan.export', ['filter' => request('filter', 'mingguan'), 'date' => request('date', date('d/m/Y'))]) }}" class="bg-green-600 hover:bg-green-700 text-white pl-4 pr-4 py-2 rounded-full text-sm font-semibold shadow-sm outline-none flex items-center gap-2">
            <span class="material-icons-outlined text-sm">download</span> Excel
        </a>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-4 mb-8">
    <button class="tab-btn {{ request('tab', 'laba-rugi') == 'laba-rugi' ? 'active bg-[#d8dbbc]' : 'bg-transparent' }} px-6 py-2 rounded-xl border border-gray-400 font-semibold flex items-center gap-2 shadow-sm transition-colors" onclick="switchTab('laba-rugi', this)">
        <span class="material-icons-outlined text-sm">analytics</span> Laba Rugi
    </button>
    <button class="tab-btn {{ request('tab') == 'rekapitulasi' ? 'active bg-[#d8dbbc]' : 'bg-transparent' }} px-6 py-2 rounded-xl border border-gray-400 font-semibold flex items-center gap-2 shadow-sm transition-colors" onclick="switchTab('rekapitulasi', this)">
        <span class="material-icons-outlined text-sm">receipt_long</span> Rekapitulasi
    </button>
</div>

<!-- Top Summary Cards -->
<div class="grid grid-cols-4 gap-6 mb-8">
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col justify-between items-center text-center">
        <div class="w-12 h-12 bg-green-700 text-white rounded-full flex items-center justify-center mb-4 shadow-sm">
            <span class="material-icons-outlined">payments</span>
        </div>
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Pendapatan Usaha</h3>
        <p class="text-2xl font-bold font-serif mb-4">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        <span class="bg-green-700 text-white text-[10px] px-4 py-1 rounded-full w-full">Total Penjualan</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col justify-between items-center text-center">
        <div class="w-12 h-12 bg-[#c62828] text-white rounded-full flex items-center justify-center mb-4 shadow-sm">
            <span class="material-icons-outlined">restaurant_menu</span>
        </div>
        <h3 class="text-sm font-semibold text-gray-700 mb-2">HPP / Bahan Baku</h3>
        <p class="text-2xl font-bold font-serif mb-4">Rp {{ number_format($totalHpp, 0, ',', '.') }}</p>
        <span class="bg-[#c62828] text-white text-[10px] px-4 py-1 rounded-full w-full">Total Pembelian</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col justify-between items-center text-center">
        <div class="w-12 h-12 bg-[#7cb342] text-white rounded-full flex items-center justify-center mb-4 shadow-sm">
            <span class="material-icons-outlined">check</span>
        </div>
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Laba Kotor</h3>
        <p class="text-2xl font-bold font-serif mb-4">Rp {{ number_format($labaKotor, 0, ',', '.') }}</p>
        <span class="bg-[#7cb342] text-white text-[10px] px-4 py-1 rounded-full w-full">Total Laba</span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col justify-between items-center text-center">
        <div class="w-12 h-12 bg-[#f57c00] text-white rounded-full flex items-center justify-center mb-4 shadow-sm">
            <span class="material-icons-outlined">bar_chart</span>
        </div>
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Margin Kotor</h3>
        <p class="text-2xl font-bold font-serif mb-4">{{ $marginKotor }}%</p>
        <span class="bg-[#f57c00] text-white text-[10px] px-4 py-1 rounded-full w-full">Total Margin (%)</span>
    </div>
</div>

<!-- Tab Content: Laba Rugi -->
<div id="laba-rugi-content" class="tab-content {{ request('tab', 'laba-rugi') == 'laba-rugi' ? 'block' : 'hidden' }}">
    <h3 class="text-xl font-bold font-serif mb-6">Laporan Laba Rugi</h3>
    
    <div class="grid grid-cols-3 gap-6">
        <!-- Pendapatan -->
        <div class="border border-gray-400 rounded-xl p-6 bg-[#eff1db] shadow-sm flex flex-col">
            <h4 class="text-sm text-gray-500 font-semibold mb-6">A. Pendapatan</h4>
            <div class="space-y-4 flex-grow">
                <div class="flex justify-between items-center border-b border-gray-400/40 pb-2">
                    <span class="font-bold text-sm">Pendapatan Total</span>
                    <span class="font-bold text-sm text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="flex justify-between items-center border-none pt-2 mt-4 mt-auto">
                <span class="font-bold text-sm">Total Pendapatan</span>
                <span class="font-bold text-sm text-green-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- HPP -->
        <div class="border border-gray-400 rounded-xl p-6 bg-[#eff1db] shadow-sm flex flex-col">
            <h4 class="text-sm text-gray-500 font-semibold mb-6">B. HPP / Bahan Baku</h4>
            <div class="space-y-4 flex-grow">
                <div class="flex justify-between items-center border-b border-gray-400/40 pb-2">
                    <span class="font-bold text-sm">Bahan Baku Total</span>
                    <span class="font-bold text-sm text-right">Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="flex justify-between items-center border-none pt-2 mt-4 mt-auto">
                <span class="font-bold text-sm">Total HPP</span>
                <span class="font-bold text-sm text-green-600">Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Formula -->
        <div class="border border-gray-400 rounded-xl p-6 bg-[#eff1db] shadow-sm flex flex-col">
            <h4 class="text-sm text-gray-500 font-semibold mb-6">C. Formula Laba Kotor</h4>
            <div class="flex-grow">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 border border-green-500 text-green-600 rounded-full text-xs font-semibold">Total Pendapatan</span>
                    <span>-</span>
                    <span class="px-3 py-1 border border-[#e53935] text-[#e53935] rounded-full text-xs font-semibold">Total HPP</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-400/40 pb-4">
                    <span class="font-bold text-sm">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                    <span>-</span>
                    <span class="font-bold text-sm">Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="flex justify-between items-center border-none pt-2 mt-4 mt-auto">
                <span class="font-bold text-sm">Total</span>
                <span class="font-bold text-sm text-green-600">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Tab Content: Rekapitulasi -->
<div id="rekapitulasi-content" class="tab-content {{ request('tab') == 'rekapitulasi' ? 'block' : 'hidden' }}">
    <div class="border border-gray-400/40 rounded-xl overflow-hidden bg-white shadow-sm">
        <div class="p-4 border-b border-gray-400/40 bg-gray-50">
            <h3 class="font-bold text-gray-800">Rekapitulasi Penjualan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#eff1db] text-gray-700 font-semibold border-b border-gray-400/40">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-right">Pendapatan</th>
                        <th class="px-6 py-3 text-right">HPP</th>
                        <th class="px-6 py-3 text-right">Laba Kotor</th>
                        <th class="px-6 py-3 text-right">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekapitulasi as $rek)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $rek->tanggal }}</td>
                        <td class="px-6 py-3 text-right text-green-700">Rp {{ number_format($rek->pendapatan, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right text-red-600">Rp {{ number_format($rek->hpp, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right text-green-700">Rp {{ number_format($rek->laba_kotor, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-medium">{{ $rek->margin }}%</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data untuk periode ini</td>
                    </tr>
                    @endforelse
                    
                    @if(count($rekapitulasi) > 0)
                    <tr class="bg-[#eff1db] font-bold border-t-2 border-gray-400">
                        <td class="px-6 py-4">Total<br><span class="text-xs font-normal">Periode Terpilih</span></td>
                        <td class="px-6 py-4 text-right text-green-700">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-red-600">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-green-700">Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">{{ $marginKotor }}%</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    function switchTab(tabId, btnElement) {
        // Update hidden input for form submission
        document.getElementById('activeTabInput').value = tabId;

        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active', 'bg-[#d8dbbc]');
            el.classList.add('bg-transparent');
        });
        
        // Show target content
        document.getElementById(tabId + '-content').classList.remove('hidden');
        document.getElementById(tabId + '-content').classList.add('block');
        
        // Set active button
        btnElement.classList.add('active', 'bg-[#d8dbbc]');
        btnElement.classList.remove('bg-transparent');
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