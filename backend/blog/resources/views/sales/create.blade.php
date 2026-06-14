@extends('layouts.app')

@section('page-title', 'Create Sale')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Create Sale</h2>
            <a href="{{ route('sales.index') }}" class="text-gray-600 hover:text-gray-900 underline text-sm transition">Back</a>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
            <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Phone</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Email</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold">Items</h3>
                        <button type="button" id="addRow"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-md text-sm transition">
                            + Add Product
                        </button>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-x-auto">
                        <table class="min-w-full" id="itemsTable">
                            <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Unit Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Line Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider"></th>
                            </tr>
                            </thead>
                            <tbody id="itemsBody">
                            <tr>
                                <td class="px-4 py-3">
                                    <select name="items[0][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition product-select" required>
                                        <option value="">-- Select --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                        data-unit-price="{{ $product->selling_price ?? 0 }}"
                                                        {{ old('items.0.product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} ({{ $product->sku ?? 'N/A' }})
                                                </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" min="1" name="items[0][quantity]" value="{{ old('items.0.quantity', 1) }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition quantity-input">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" min="0" step="0.01" name="items[0][unit_price]" value="{{ old('items.0.unit_price', 0) }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition price-input">
                                </td>
                                <td class="px-4 py-3">
                                    <span class="line-total text-sm font-semibold text-gray-900">0</span>
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button" class="removeRow bg-red-50 hover:bg-red-100 text-red-700 font-semibold py-1.5 px-3 rounded-md text-sm transition" style="display:none;">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="text-sm text-gray-600">
                        Grand Total: <span id="grandTotal" class="font-semibold text-gray-900">0</span>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md text-sm transition shadow-sm">
                            Generate Receipt
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const itemsBody = document.getElementById('itemsBody');
            const addRowBtn = document.getElementById('addRow');
            const grandTotalEl = document.getElementById('grandTotal');

            function recalcRow(tr) {
                const qty = parseFloat(tr.querySelector('.quantity-input').value || 0);
                const price = parseFloat(tr.querySelector('.price-input').value || 0);
                const total = qty * price;
                tr.querySelector('.line-total').textContent = total.toFixed(2);
                return total;
            }

            function recalcGrandTotal() {
                let sum = 0;
                itemsBody.querySelectorAll('tr').forEach(tr => {
                    sum += recalcRow(tr);
                });
                grandTotalEl.textContent = sum.toFixed(2);
            }

            function wireRow(tr) {
                tr.querySelector('.quantity-input').addEventListener('input', recalcGrandTotal);
                tr.querySelector('.price-input').addEventListener('input', recalcGrandTotal);

                const productSelect = tr.querySelector('.product-select');
                if (productSelect) {
                    productSelect.addEventListener('change', () => {
                        const selected = productSelect.options[productSelect.selectedIndex];
                        const unitPrice = selected?.dataset?.unitPrice;
                        const priceInput = tr.querySelector('.price-input');
                        if (priceInput && unitPrice !== undefined) {
                            priceInput.value = parseFloat(unitPrice) || 0;
                        }
                        recalcGrandTotal();
                    });

                    // Initialize unit price if product already selected
                    const selected = productSelect.options[productSelect.selectedIndex];
                    const unitPrice = selected?.dataset?.unitPrice;
                    const priceInput = tr.querySelector('.price-input');
                    if (priceInput && unitPrice !== undefined) {
                        priceInput.value = parseFloat(unitPrice) || 0;
                    }
                }
            }

            addRowBtn.addEventListener('click', () => {
                const index = itemsBody.querySelectorAll('tr').length;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-3">
                        <select name="items[${index}][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition product-select" required>
                            <option value="">-- Select --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ addslashes($product->name) }} ({{ addslashes($product->sku ?? 'N/A') }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" min="1" name="items[${index}][quantity]" value="1" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition quantity-input">
                    </td>
                    <td class="px-4 py-3">
                        <input type="number" min="0" step="0.01" name="items[${index}][unit_price]" value="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition price-input">
                    </td>
                    <td class="px-4 py-3">
                        <span class="line-total text-sm font-semibold text-gray-900">0</span>
                    </td>
                    <td class="px-4 py-3">
                        <button type="button" class="removeRow bg-red-50 hover:bg-red-100 text-red-700 font-semibold py-1.5 px-3 rounded-md text-sm transition">
                            Remove
                        </button>
                    </td>
                `;

                itemsBody.appendChild(tr);

                tr.querySelector('.removeRow').addEventListener('click', () => {
                    tr.remove();
                    recalcGrandTotal();
                });

                // Hide remove button for the first row (handled below by index count)
                if (itemsBody.querySelectorAll('tr').length === 1) {
                    itemsBody.querySelectorAll('.removeRow').forEach(b => b.style.display = 'none');
                } else {
                    itemsBody.querySelectorAll('tr')[0].querySelector('.removeRow').style.display = 'none';
                }

                wireRow(tr);
                recalcGrandTotal();
            });

            // Wire initial row
            itemsBody.querySelectorAll('tr').forEach(wireRow);
            recalcGrandTotal();

            // Keep first row remove hidden
            const rows = itemsBody.querySelectorAll('tr');
            if (rows.length) {
                rows[0].querySelector('.removeRow').style.display = 'none';
            }
        })();
    </script>
@endsection

