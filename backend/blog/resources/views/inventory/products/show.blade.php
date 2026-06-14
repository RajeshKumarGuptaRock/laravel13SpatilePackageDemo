@extends('inventory.layouts.app')

@section('page-title', $product->name)

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-200">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-500">SKU: {{ $product->sku ?? '-' }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('inventory.products.index') }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-md transition shadow-sm border border-gray-200">Back</a>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Description</div>
                    <div class="text-gray-800 whitespace-pre-wrap">{{ $product->description ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm font-semibold text-gray-700 mb-1">Category</div>
                        <div class="text-gray-800">{{ optional($product->category)->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-700 mb-1">Supplier</div>
                        <div class="text-gray-800">{{ optional($product->supplier)->name ?? '-' }}</div>
                    </div>
                </div>

                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Min Stock</div>
                    <div class="text-gray-800">{{ $product->min_stock }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

