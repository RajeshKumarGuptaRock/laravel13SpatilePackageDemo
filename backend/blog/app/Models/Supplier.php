<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['email', 'name', 'updated_at', 'created_at', 'address', 'phone'])]
class Supplier extends Model
{
    //
}
