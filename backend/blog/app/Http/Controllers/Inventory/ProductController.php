<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $products = Product::query()
            ->with(['category', 'supplier'])
            ->withSum(['stockMovements as available_in_qty' => function ($q) {
                $q->where('movement_type', 'in');
            }], 'quantity')
            ->withSum(['stockMovements as available_out_qty' => function ($q) {
                $q->where('movement_type', 'out');
            }], 'quantity')
            ->latest()
            ->paginate(10);

        // Compute available qty (in - out) for each product.
        // available_in_qty / available_out_qty are NOT columns on products table;
        // they are appended dynamically by the earlier withSum() calls.
        $products->getCollection()->transform(function ($product) {
            $in = (int) ($product->available_in_qty ?? 0);
            $out = (int) ($product->available_out_qty ?? 0);
            $product->available_qty = $in - $out;
            return $product;
        });

        return view('inventory.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::query()->orderBy('name')->get();
        $suppliers = \App\Models\Supplier::query()->orderBy('name')->get();

        return view('inventory.products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'barcode' => ['required', 'string'],
            'purchase_price' => ['required', 'decimal:2'],
            'selling_price' => ['required', 'decimal:2'],
            'mfd_date' => ['required', 'date'],
            'exp_date' => ['required', 'date'],
        ]);

        Product::create($validated);

        return redirect()->route('inventory.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);

        return view('inventory.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = \App\Models\Category::query()->orderBy('name')->get();
        $suppliers = \App\Models\Supplier::query()->orderBy('name')->get();

        return view('inventory.products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($validated);

        return redirect()->route('inventory.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('inventory.products.index')->with('success', 'Product deleted successfully.');
    }
}
