@props([
    'title' => 'Manajemen Stok Barang',
    'subtitle' => 'Sistem Manajemen Stok StockMaster',
    'showTabs' => true,
    'activeTab' => 'overview',
    'showBanner' => true
])

@if($showBanner)
<!-- Banner -->
<div id="welcome-banner" class="sticky top-0 z-40 flex flex-row items-center justify-center px-4 text-center text-sm font-medium transition-all duration-300" style="height: 3rem; background: linear-gradient(90deg, #dcfce7 0%, #fce7f3 100%);">
    <span class="text-gray-800 font-medium">🎉 New Features coming soon!</span>
    <button type="button" onclick="closeBanner()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-800 w-6 h-6 flex items-center justify-center rounded-full hover:bg-white/20 transition-colors">
        <i class="fas fa-times text-xs"></i>
    </button>
</div>
@endif

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-2">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">{{ $title }}</h1>
            <div class="flex items-center space-x-4">
                <!-- Date Range Picker -->
                <div class="flex items-center space-x-2 bg-white border border-gray-300 rounded-md px-3 py-2">
                    <i class="fas fa-calendar text-gray-400 text-sm"></i>
                    <span class="text-sm text-gray-600">{{ date('M d, Y') }} - {{ date('M d, Y', strtotime('+20 days')) }}</span>
                </div>
                <!-- Download Button -->
                <button class="bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition-colors">
                    import
                </button>
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleProfileDropdown()" class="flex items-center space-x-2 bg-white border border-gray-300 rounded-md px-3 py-2 hover:bg-gray-50 transition-colors">
                        <div class="w-6 h-6 bg-gray-900 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 hidden z-50">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($showTabs)
        <!-- Navigation Tabs - Compact background that fits content -->
        <nav class="inline-flex space-x-2 bg-gray-100 p-1 rounded-lg" id="tabNavigation">
            <a href="{{ route('dashboard') }}" 
               class="tab-button {{ $activeTab === 'overview' ? 'active' : '' }} px-4 py-2 text-sm font-medium transition-all duration-200 rounded-md {{ $activeTab === 'overview' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-white/50' }} no-underline">
                Overview
            </a>
            <a href="{{ route('barang.index') }}" 
               class="tab-button {{ $activeTab === 'barang' ? 'active' : '' }} px-4 py-2 text-sm font-medium transition-all duration-200 rounded-md {{ $activeTab === 'barang' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-white/50' }} no-underline">
                Barang
            </a>
            <a href="{{ route('transaksi.index') }}" 
               class="tab-button {{ $activeTab === 'transaksi' ? 'active' : '' }} px-4 py-2 text-sm font-medium transition-all duration-200 rounded-md {{ $activeTab === 'transaksi' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-white/50' }} no-underline">
                Transaksi
            </a>
            <a href="{{ route('laporan.pareto') }}" 
               class="tab-button {{ $activeTab === 'analisis' ? 'active' : '' }} px-4 py-2 text-sm font-medium transition-all duration-200 rounded-md {{ $activeTab === 'analisis' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-800 hover:bg-white/50' }} no-underline">
                Analisis
            </a>
        </nav>
        @endif
    </div>
</div>

<script>
    // Profile dropdown functionality
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const button = event.target.closest('[onclick="toggleProfileDropdown()"]');
        
        if (!button && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Close banner functionality
    function closeBanner() {
        const banner = document.getElementById('welcome-banner');
        if (banner) {
            banner.style.height = '0';
            banner.style.opacity = '0';
            setTimeout(() => {
                banner.style.display = 'none';
            }, 300);
            localStorage.setItem('banner-welcome-banner', 'true');
        }
    }

    // Check if banner should be hidden
    if (localStorage.getItem('banner-welcome-banner') === 'true') {
        const banner = document.getElementById('welcome-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    }
</script>

<style>
    /* Tab Styles */
    .tab-button {
        position: relative;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }

    .tab-button.active {
        color: #111827;
        background-color: white;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .tab-button:hover {
        text-decoration: none !important;
    }

    .no-underline {
        text-decoration: none !important;
    }

    .no-underline:hover {
        text-decoration: none !important;
    }
</style>
