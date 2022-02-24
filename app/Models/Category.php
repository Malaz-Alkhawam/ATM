<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    use HasFactory;
    function peoducts()
    {
        return $this->hasMany(Product::class, 'product_id', 'id');
    }
}
