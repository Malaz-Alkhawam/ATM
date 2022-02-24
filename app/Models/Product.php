<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    public $table = 'products';
    use HasFactory, HasApiTokens;
    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    function seens()
    {
        return $this->hasMany(Seen::class);
    }
    function likes()
    {
        return $this->hasMany(Like::class);
    }
    function comments()
    {
        return $this->hasMany(Comments::class);
    }
    function prices()
    {
        return $this->hasMany(PriceRange::class, 'product_id');
    }
}
