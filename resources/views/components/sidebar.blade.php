<aside class="w-64 h-screen bg-[#c5cb9f] p-6 flex flex-col fixed left-0 top-0 -translate-x-full md:translate-x-0 z-50 transition-transform duration-300"
       :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="mb-10">
        <h1 class="text-xl font-bold text-[#6a4f21]">Soto Mba Ratih</h1>
        <p class="text-xs text-[#6a4f21]/75 font-medium">Point of Sales</p>
    </div>

    <nav class="space-y-1 flex-grow">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('dashboard') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-[#5a4a31] hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">dashboard</span>
            <span>Dashboard</span>
        </a>

        @if(Auth::user()->role === 'Kasir')
        <a href="{{ route('transaksi') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('transaksi*') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-[#5a4a31] hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">shopping_cart</span>
            <span>Transaksi</span>
        </a>
        @endif

        <a href="{{ route('menu') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('menu', 'kategori.*', 'produk.*') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-[#5a4a31] hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">menu_book</span>
            <span>Menu</span>
        </a>

        <a href="{{ route('sesi-kasir') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('sesi-kasir*') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-[#5a4a31] hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">calculate</span>
            <span>Sesi Kasir</span>
        </a>

        <a href="{{ route('buku-kasir') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('buku-kasir*') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-[#5a4a31] hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">book</span>
            <span>Buku Kasir</span>
        </a>

        @if(Auth::user()->role !== 'Kasir')
        <a href="{{ route('bahan-baku.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('bahan-baku.*') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-[#5a4a31] hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">restaurant</span>
            <span>Bahan Baku</span>
        </a>
        <a href="{{ route('laporan') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition font-medium text-sm {{ request()->routeIs('laporan') ? 'bg-[#f4ebd0] text-[#785b27]' : 'text-[#5a4a31] hover:bg-white/20' }}">
            <span class="material-icons-outlined !text-[20px]">receipt_long</span>
            <span>Laporan</span>
        </a>
        @endif
    </nav>

    <div class="mt-auto border-t border-[#6a4f21]/20 pt-4 px-2" x-data="jam()">
        <div class="flex items-center space-x-3 text-[#5a4a31]">
            <div class="w-8 h-8 rounded-full bg-white/40 border border-[#6a4f21]/20 flex items-center justify-center flex-shrink-0">
                <span class="material-icons-outlined !text-[18px]">person</span>
            </div>
            <span class="font-bold text-[14px] flex-1 truncate">
                {{ Auth::user() ? Auth::user()->role : 'Kasir' }} : {{ Auth::user() ? ucfirst(Auth::user()->username) : 'Naomi' }} 
            </span>
            <span class="font-bold text-[14px]" x-text="waktu"></span>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="w-full py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-sm transition shadow-sm">
                Logout
            </button>
        </form>
    </div>
</aside>