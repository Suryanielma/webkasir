@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-950">Dashboard</h2>
    <form id="dateForm" action="{{ route('dashboard') }}" method="GET" class="relative group w-full sm:w-auto">
        <input type="text" name="date" id="datePicker" class="bg-[#d8dbbc] text-[#333] pl-4 pr-8 py-2 rounded-full text-xs font-semibold cursor-pointer shadow-sm outline-none w-full sm:w-28 text-center" value="{{ $dateStr }}" onchange="document.getElementById('dateForm').submit()" readonly>
        <span class="material-icons-outlined text-xs absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-[#333]">arrow_drop_down</span>
    </form>
</div>

<div class="flex flex-col gap-6">
    <div class="flex flex-col lg:flex-row gap-6">
        
        <div class="flex flex-col gap-6 w-full lg:w-1/4">
            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-48 flex flex-col justify-between shadow-sm">
                <h3 class="text-base font-semibold text-gray-800">Transaksi<br>Hari Ini</h3>
                <div>
                    <div class="text-3xl font-bold text-gray-900 my-2">{{ $transaksiHariIni }}</div>
                    <div class="bg-[#8b9967] text-white text-[10px] px-3 py-1 rounded-full w-max font-medium shadow-sm">Transaksi Selesai</div>
                </div>
            </div>

            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-48 flex flex-col justify-between shadow-sm">
                <h3 class="text-base font-semibold text-gray-800">Pendapatan</h3>
                <div>
                    <div class="text-3xl font-bold text-gray-900 my-2">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                    <div class="bg-[#e45126] text-white text-[10px] px-3 py-1 rounded-full w-max font-medium shadow-sm">Rupiah (IDR)</div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6 w-full lg:w-1/4">
            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-48 flex flex-col justify-between shadow-sm">
                <h3 class="text-base font-semibold text-gray-800">Menu<br>Tersedia</h3>
                <div>
                    <div class="text-3xl font-bold text-gray-900 my-2">{{ $menuTersedia }}</div>
                    <div class="bg-[#4a8a25] text-white text-[10px] px-3 py-1 rounded-full w-max font-medium shadow-sm">Dari {{ $totalMenu }} Menu</div>
                </div>
            </div>

            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-48 flex flex-col justify-between shadow-sm">
                <h3 class="text-base font-semibold text-gray-800">Menu Habis</h3>
                <div>
                    <div class="text-3xl font-bold text-gray-900 my-2">{{ $menuHabis }}</div>
                    <div class="bg-[#d9822b] text-white text-[10px] px-3 py-1 rounded-full w-max font-medium shadow-sm">Perlu Diisi Ulang</div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-2/4">
            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-full flex flex-col shadow-sm min-h-[310px]">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Grafik Penjualan</h3>
                <div class="flex-grow w-full relative h-48">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        <div class="w-full lg:w-1/4">
            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-full flex flex-col items-center justify-center text-center shadow-sm min-h-[220px]">
                <h3 class="text-base font-semibold text-gray-800 mb-6 w-full text-left">Item Terjual</h3>
                <span class="material-icons-outlined text-[#7a5924] text-3xl mb-4">fastfood</span>
                <div class="text-3xl font-bold text-gray-900 mb-4">{{ $itemTerjualHariIni ?? 0 }}</div>
                <div class="bg-[#7a5924] text-white text-[10px] px-3 py-1 rounded-full w-max font-medium shadow-sm">Porsi Per-hari ini</div>
            </div>
        </div>

        <div class="w-full lg:w-[37.5%]">
            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-full shadow-sm">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Menu Terlaris</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="border-b border-gray-400/40 font-semibold text-gray-800">
                                <th class="py-2 pb-3 w-10">#</th>
                                <th class="py-2 pb-3">Nama Menu</th>
                                <th class="py-2 pb-3 text-right">Terjual</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($menuTerlaris as $index => $item)
                            <tr class="border-b border-gray-400/40 last:border-0">
                                <td class="py-3">
                                    <span class="bg-[#9e9d7c] text-white w-6 h-6 rounded-full inline-flex items-center justify-center text-[10px]">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-3 font-medium">{{ $item->nama_produk }}</td>
                                <td class="py-3 text-right text-green-600 font-semibold whitespace-nowrap">{{ $item->total_terjual }} Porsi</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-3 text-center text-gray-400">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[37.5%]">
            <div class="border border-gray-400/40 rounded-2xl p-6 bg-transparent h-full shadow-sm max-h-64 overflow-y-auto relative">
                <h3 class="text-base font-semibold text-gray-800 mb-4 sticky top-0 bg-[#f7f5ed] py-1 z-10 -mx-2 px-2">Status Menu</h3>
                
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="border-b border-gray-400/40 font-semibold text-gray-800">
                            <th class="py-2 pb-3">Menu</th>
                            <th class="py-2 pb-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($statusMenu as $sm)
                        <tr class="border-b border-gray-400/40 last:border-0">
                            <td class="py-3 font-medium">{{ $sm->nama_produk }}</td>
                            <td class="py-3 flex items-center justify-end gap-2 text-right">
                                <span class="w-2 h-2 rounded-full {{ strtolower($sm->status) == 'tersedia' ? 'bg-green-500' : 'bg-red-500' }}"></span> 
                                <span class="font-medium">{{ ucfirst($sm->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Kalender Tanggal
        flatpickr("#datePicker", {
            dateFormat: "d/m/Y",
            defaultDate: "{{ $dateStr }}",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                document.getElementById('dateForm').submit();
            }
        });

        // Inisialisasi Grafik Penjualan
        if(typeof Chart !== 'undefined') {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(235, 76, 32, 0.4)');
            gradient.addColorStop(1, 'rgba(235, 76, 32, 0)');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($grafikLabel) !!},
                    datasets: [{
                        label: 'Penjualan',
                        data: {!! json_encode($grafikPenjualan) !!},
                        borderColor: '#e24d17',
                        borderWidth: 3,
                        pointBackgroundColor: '#e24d17',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#e24d17',
                        pointRadius: 4, // Diubah jadi 4 agar titik data kelihatan di HP/Tablet
                        pointHoverRadius: 6,
                        fill: true,
                        backgroundColor: gradient,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            // Aturan 'max: 1000000' DIHAPUS agar grafik bisa auto-scale otomatis
                            ticks: {
                                callback: function(value) {
                                    if(value === 0) return '0';
                                    return value >= 1000 ? (value / 1000) + 'k' : value;
                                },
                                font: { family: "'Instrument Sans', sans-serif", size: 11 },
                                color: '#666'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Instrument Sans', sans-serif", size: 11 }, color: '#666' }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection