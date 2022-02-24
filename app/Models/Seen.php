<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seen extends Model
{
    protected $table = 'seens';
    use HasFactory;
    function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
