<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    use HasFactory;
    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
