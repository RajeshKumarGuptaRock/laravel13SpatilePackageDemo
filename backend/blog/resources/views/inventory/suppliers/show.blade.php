@extends('inventory.layouts.app')

@section('page-title', $supplier->name)

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-200">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $supplier->name }}</h2>
                    <p class="text-sm text-gray-500">Supplier details</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('inventory.suppliers.index') }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-md transition shadow-sm border border-gray-200">Back</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Email</div>
                    <div class="text-gray-800">{{ $supplier->email ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Phone</div>
                    <div class="text-gray-800">{{ $supplier->phone ?? '-' }}</div>
                </div>
                <div class="sm:col-span-2">
                    <div class="text-sm font-semibold text-gray-700 mb-1">Address</div>
                    <div class="text-gray-800 whitespace-pre-wrap">{{ $supplier->address ?? '-' }}</div>
                </div>
            </div>

            <div class="mt-6">
                @can('edit suppliers')
                    <a href="{{ route('inventory.suppliers.edit', $supplier) }}"
                       class="inline-flex items-center px-4 py-2 bg-amber-500 text-xs text-white uppercase hover:bg-amber-600 transition rounded-md shadow-sm">
                        Edit
                    </a>
                @endcan
            </div>
        </div>
    </div>
@endsection

