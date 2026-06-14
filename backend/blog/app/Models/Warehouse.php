<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name', 'updated_at', 'created_at', 'address'])]
class Warehouse extends Model
{
    //
}
