@extends('inventory.layouts.app')

@section('page-title', $category->name)

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-200">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $category->name }}</h2>
                    <p class="text-sm text-gray-500">Category details</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('inventory.categories.index') }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-md transition shadow-sm border border-gray-200">Back</a>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Name</div>
                    <div class="text-gray-800">{{ $category->name }}</div>
                </div>
            </div>

            <div class="mt-6">
                @can('edit categories')
                    <a href="{{ route('inventory.categories.edit', $category) }}"
                       class="inline-flex items-center px-4 py-2 bg-amber-500 text-xs text-white uppercase hover:bg-amber-600 transition rounded-md shadow-sm">
                        Edit
                    </a>
                @endcan
            </div>
        </div>
    </div>
@endsection

