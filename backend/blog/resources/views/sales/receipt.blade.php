@extends('layouts.receipt')

@section('page-title', 'Receipt')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Sales Receipt</h2>
            <div class="flex gap-3">
                <a href="{{ route('sales.create') }}" class="text-gray-600 hover:text-gray-900 underline text-sm transition">New Sale</a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs uppercase tracking-wider text-gray-500">Customer Name</div>
                        <div class="text-sm font-semibold text-gray-900">{{ $customerName }}</div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wider text-gray-500">Contact</div>
                        <div class="text-sm text-gray-800">
                            @if(!empty($customerPhone)){{ $customerPhone }}@endif
                            @if(!empty($customerEmail)){{ $customerPhone ? ' | ' : '' }}{{ $customerEmail }}@endif
                            @if(empty($customerPhone) && empty($customerEmail))-@endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Unit Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Line Total</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @forelse($items as $item)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $item['sku'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['name'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $item['quantity'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ number_format((float)$item['unit_price'], 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format((float)$item['line_total'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">No items.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <div class="text-right">
                        <div class="text-xs uppercase tracking-wider text-gray-500">Grand Total</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format((float)$grandTotal, 2) }}</div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-md text-sm transition shadow-sm">
                        Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

