<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }

    public function create()
    {
        $products = Product::query()->orderBy('name')->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $lines = collect($validated['items'])->map(function ($item) {
            $qty = (int) $item['quantity'];
            $unitPrice = (float) $item['unit_price'];
            $lineTotal = $qty * $unitPrice;

            return [
                'product_id' => (int) $item['product_id'],
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        });

        $grandTotal = $lines->sum('line_total');

        // Decrease inventory quantity on sale (movement_type: out)
        // Uses default warehouse (first warehouse) as warehouse_id.
        $warehouseId = \App\Models\Warehouse::query()->orderBy('id')->value('id');

        // In case the model is not loaded as expected, ensure a scalar id value.
        // value('id') can return null if no rows exist.
        // Ensure scalar
        // (Eloquent value() already returns scalar, but keep this harmless)
        $warehouseId = $warehouseId ? (int) $warehouseId : null;

        if (!$warehouseId) {
            return back()->withErrors([
                'items' => 'No warehouse found. Please create a warehouse before recording sales.',
            ])->withInput();
        }

        $productsById = Product::query()->get()->keyBy('id');


        foreach ($lines as $line) {
            $product = $productsById->get($line['product_id']);
            if (!$product) {
                continue;
            }

            // NOTE: Product model does not currently have a stock quantity column.
            // So we only create stock movement records to track 'out'.
            // If later you add a `stock_quantity` field, we can also update it here.

            StockMovement::query()->create([
                'product_id' => $line['product_id'],
                'warehouse_id' => $warehouseId,
                'movement_type' => 'out',
                'quantity' => $line['quantity'],
                'reference' => 'sale',
                'user_id' => auth()->id(),
            ]);
        }

        $receiptItems = $lines->map(function ($line) use ($productsById) {
            $product = $productsById->get($line['product_id']);

            return [
                'product_id' => $line['product_id'],
                'sku' => $product?->sku,
                'name' => $product?->name,
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'line_total' => $line['line_total'],
            ];
        });

        return view('sales.receipt', [
            'customerName' => $validated['customer_name'],
            'customerPhone' => $validated['customer_phone'] ?? null,
            'customerEmail' => $validated['customer_email'] ?? null,
            'items' => $receiptItems,
            'grandTotal' => $grandTotal,
        ]);
    }

    // Minimal stubs to satisfy resource routes until we add full persistence.
    public function show($sale)
    {
        abort(404);
    }

    public function edit($sale)
    {
        abort(404);
    }

    public function update(Request $request, $sale)
    {
        abort(404);
    }

    public function destroy($sale)
    {
        abort(404);
    }
}
