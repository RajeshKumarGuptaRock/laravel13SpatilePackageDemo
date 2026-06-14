<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['exp_date', 'mfd_date', 'barcode', 'purchase_price', 'selling_price', 'sku', 'category_id', 'supplier_id', 'updated_at', 'created_at', 'min_stock', 'name'])]
class Product extends Model
{
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
