<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'comments';
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
