<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{

    use HasFactory, HasApiTokens;
    protected $table = 'users';
    protected $fillable = ['name', 'password'];
    function products()
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }
    function seens()
    {
        return $this->hasMany(Seen::class, 'seen_id', 'id');
    }
    function comments()
    {
        return $this->hasMany(Comments::class, 'comment_id', 'id');
    }
}
