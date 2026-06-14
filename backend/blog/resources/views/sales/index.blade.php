@extends('layouts.app')

@section('page-title', 'Sales')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Sales</h2>
        <a href="{{ route('sales.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition">
            Create Sale
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-x-auto">

        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            <tr>
                <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">
                    Sales view placeholder. No sales data implemented yet.
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection

