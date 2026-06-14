@php
    $activeArticles = request()->routeIs('articles.*');
    $activeInventory = request()->routeIs('inventory.*');
@endphp

<div class="p-4">
    <div class="mb-6">
        <div class="text-sm font-bold text-gray-900">Admin</div>
        <div class="text-xs text-gray-500">Navigation</div>
    </div>

    <nav class="space-y-2">
        @if(Route::has('dashboard'))
            <a href="{{ url('/dashboard') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ request()->routeIs('dashboard') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Dashboard
            </a>
        @endif

        <div class="mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">CMS</div>
        {{-- Articles --}}
        <a href="{{ route('articles.index') }}"
           class="block px-3 py-2 rounded-md text-sm font-medium transition border
               {{ $activeArticles ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
            Articles
        </a>

        <div class="mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Inventory</div>

        {{-- <a href="{{ route('inventory.index') }}"
           class="block px-3 py-2 rounded-md text-sm font-medium transition border
               {{ $activeInventory && request()->routeIs('inventory.index') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
            Inventory Dashboard
        </a> --}}
        <a href="{{ route('sales.index') }}"
           class="block px-3 py-2 rounded-md text-sm font-medium transition border
               {{ request()->routeIs('sales.index.*') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
            Sales
        </a>

        <a href="{{ route('inventory.products.index') }}"
           class="block px-3 py-2 rounded-md text-sm font-medium transition border
               {{ request()->routeIs('inventory.products.*') && !request()->routeIs('inventory.products.create') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
            Products
        </a>

       

        @can('view categories')
            <a href="{{ route('inventory.categories.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ request()->routeIs('inventory.categories.*') && !request()->routeIs('inventory.categories.create') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Categories
            </a>
        @endcan


        @can('manage stock')
            <a href="{{ route('inventory.stock.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ request()->routeIs('inventory.stock.*') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Stock Movements
            </a>

            <a href="{{ route('inventory.stock.create') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ request()->routeIs('inventory.stock.create') ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Record Movement
            </a>
        @elsecan('view stock')
            <a href="{{ route('inventory.stock.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ request()->routeIs('inventory.stock.*') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Stock Movements
            </a>
        @endcan

         @can('create products')
            <a href="{{ route('inventory.stock.create') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ request()->routeIs('inventory.products.create') ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Create Stock Movements
            </a>
        @endcan

        <a href="{{ route('inventory.suppliers.index') }}"
           class="block px-3 py-2 rounded-md text-sm font-medium transition border
               {{ request()->routeIs('inventory.suppliers.*') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
            Suppliers
        </a>

      

        <a href="{{ route('inventory.warehouses.index') }}"
           class="block px-3 py-2 rounded-md text-sm font-medium transition border
               {{ request()->routeIs('inventory.warehouses.*') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
            Warehouses
        </a>

    </nav>
</div>


