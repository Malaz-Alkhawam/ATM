<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceRange extends Model
{
    protected $table = 'prices';
    use HasFactory;
    function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
