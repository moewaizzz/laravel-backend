<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['quantity',"recipe_id","cart_id"];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
