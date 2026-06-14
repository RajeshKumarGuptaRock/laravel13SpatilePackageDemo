@php
    $activeProducts = request()->routeIs('inventory.products*');
    $activeStock = request()->routeIs('inventory.stock*');
    $activeSuppliers = request()->routeIs('inventory.suppliers*');
    $activeWarehouses = request()->routeIs('inventory.warehouses*');
@endphp

<div class="p-4">
    <div class="mb-6">
        <div class="text-sm font-bold text-gray-900">Inventory</div>
        <div class="text-xs text-gray-500">Stock & Master Data</div>
    </div>

    <nav class="space-y-2">
        <a href="{{ route('inventory.index') }}"
           class="block px-3 py-2 rounded-md text-sm font-medium transition border
               {{ request()->routeIs('inventory.index') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
            Dashboard
        </a>

        @can('view products')
            <a href="{{ route('inventory.products.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ $activeProducts && !request()->routeIs('inventory.products.create') ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Products
            </a>
        @endcan

        @can('create products')
            <a href="{{ route('inventory.products.create') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ request()->routeIs('inventory.products.create') ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Create Product
            </a>
        @endcan


        @can('manage stock')
            <a href="{{ route('inventory.stock.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ $activeStock ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Stock Movements
            </a>
        @endcan

        @can('view suppliers')
            <a href="{{ route('inventory.suppliers.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ $activeSuppliers ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Suppliers
            </a>
        @endcan

        @can('view warehouses')
            <a href="{{ route('inventory.warehouses.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium transition border
                   {{ $activeWarehouses ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-white border-transparent text-gray-700 hover:bg-gray-50 hover:border-gray-200' }}">
                Warehouses
            </a>
        @endcan
    </nav>
</div>

