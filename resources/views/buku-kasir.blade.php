@extends('layouts.app')

@section('content')
<div class="flex flex-col h-full mt-[-10px] space-y-6">
    <div class="mb-4">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Buku Kasir</h2>
        <p class="text-sm font-medium text-gray-500">Riwayat sesi buka tutup kasir dan transaksi</p>
    </div>
    
    <div class="border-t border-black/20 mb-6"></div>

    <div class="border border-black/30 rounded-2xl overflow-hidden bg-[#f7f4e9] shadow-sm">
        <div class="overflow-x-auto">
            
            {{-- KUNCINYA DI SINI: Mengunci min-w-[950px] sampai layar benar-benar luas (xl:min-w-0) 
                 Layar tablet akan otomatis mendapatkan scrollbar horizontal yang rapi tanpa merusak teks --}}
            <div class="w-full min-w-[950px] xl:min-w-0">
                
                <div class="bg-[#c5cb9f] grid grid-cols-6 px-6 py-4 border-b border-black/30 text-left">
                    <div class="font-bold text-gray-900 text-sm">ID Sesi</div>
                    <div class="font-bold text-gray-900 text-sm">Waktu Buka</div>
                    <div class="font-bold text-gray-900 text-sm">Waktu Tutup</div>
                    <div class="font-bold text-gray-900 text-sm">Kasir</div>
                    <div class="font-bold text-gray-900 text-sm">Total Transaksi</div>
                    <div class="font-bold text-gray-900 text-sm">Total Pendapatan</div>
                </div>

                @if($riwayatSesi && $riwayatSesi->isNotEmpty())
                    @foreach($riwayatSesi as $sesi)
                    <div class="grid grid-cols-6 px-6 py-4 border-b border-black/10 items-center last:border-b-0 hover:bg-black/5 transition">
                        
                        <div class="font-bold text-sm">
                            <a href="{{ route('buku-kasir.detail', $sesi->id_sesi) }}" class="text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1 whitespace-nowrap">
                                <span class="material-icons-outlined !text-[16px]">history</span>
                                SESI-{{ $sesi->id_sesi }}
                            </a>
                        </div>
                        
                        <div class="font-semibold text-gray-800 text-sm whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($sesi->waktu_buka)->format('d M Y H:i') }}
                        </div>
                        
                        <div class="font-bold text-sm pr-2">
                            @if($sesi->waktu_tutup)
                                <span class="text-gray-600 font-semibold whitespace-nowrap">{{ \Carbon\Carbon::parse($sesi->waktu_tutup)->format('d M Y H:i') }}</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-green-600 border border-green-200 px-2.5 py-0.5 rounded-full font-bold whitespace-nowrap shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Sesi Aktif
                                </span>
                            @endif
                        </div>
                        
                        <div class="font-semibold text-gray-800 text-sm truncate pr-2">
                            {{ $sesi->user->username ?? 'Unknown' }}
                        </div>
                        
                        <div class="font-bold text-gray-700 text-sm whitespace-nowrap">
                            {{ $sesi->transaksiPenjualan->count() }} Trx
                        </div>
                        
                        <div class="font-bold text-gray-900 text-sm whitespace-nowrap">
                            Rp {{ number_format($sesi->transaksiPenjualan->sum('total_harga'), 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="px-6 py-16 flex flex-col items-center justify-center text-gray-400 gap-2">
                        <span class="material-icons-outlined !text-[40px] opacity-40">menu_book</span>
                        <p class="text-sm font-bold">Belum ada riwayat sesi kasir.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection