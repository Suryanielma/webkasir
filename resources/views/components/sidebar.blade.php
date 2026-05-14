<aside class="w-64 h-screen bg-[#c5cb9f] p-6 flex flex-col fixed left-0 top-0">
    <div class="mb-10">
        <h1 class="text-xl font-bold text-[#6a4f21]">Soto Mba Ratih</h1>
        <p class="text-xs text-gray-500">Post Of Sales</p>
    </div>

    <nav class="space-y-1 flex-grow">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('dashboard') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-white hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('transaksi') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('transaksi') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-white hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">shopping_cart</span>
            <span>Transaksi</span>
        </a>
        <a href="{{ route('menu') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('menu', 'kategori.*', 'produk.*') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-white hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">menu_book</span>
            <span>Menu</span>
        </a>

        @if(Auth::user()->role !== 'Kasir')
        <a href="{{ route('bahan-baku') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('bahan-baku') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-white hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">restaurant</span>
            <span>Bahan Baku</span>
        </a>
        <a href="{{ route('laporan') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('laporan') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-white hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">receipt_long</span>
            <span>Laporan</span>
        </a>
        @endif
    </nav>
</aside>