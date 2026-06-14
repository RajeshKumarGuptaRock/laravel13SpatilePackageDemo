<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        $stockMovements = StockMovement::query()
            ->with(['product', 'warehouse'])
            ->latest()
            ->paginate(10);

        return view('inventory.stock.index', compact('stockMovements'));
    }

    public function create()
    {
        $products = Product::query()->orderBy('name')->get();
        $warehouses = Warehouse::query()->orderBy('name')->get();

        return view('inventory.stock.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'movement_type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        StockMovement::create($validated);

        return redirect()->route('inventory.stock.index')->with('success', 'Stock movement recorded successfully.');
    }

    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product', 'warehouse']);

        return view('inventory.stock.show', compact('stockMovement'));
    }

    public function edit(StockMovement $stockMovement)
    {
        $products = Product::query()->orderBy('name')->get();
        $warehouses = Warehouse::query()->orderBy('name')->get();

        return view('inventory.stock.edit', compact('stockMovement', 'products', 'warehouses'));
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'movement_type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $stockMovement->update($validated);

        return redirect()->route('inventory.stock.index')->with('success', 'Stock movement updated successfully.');
    }

    public function destroy(StockMovement $stockMovement)
    {
        $stockMovement->delete();

        return redirect()->route('inventory.stock.index')->with('success', 'Stock movement deleted successfully.');
    }
}
