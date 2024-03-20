<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    
    protected $fillable = ['title','description','category_id','photo', 'price'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function scopeBestSeller($builder){
        return $builder->where('best_seller',1)->get();
    }

    public function scopeFilter($query,$filters){
        if(isset($filters['category'])){
            $query->whereHas('category',function ($catQuery) use ($filters){
                $catQuery->where('name',$filters['category']);
            });
        }
        // $query->where('category_id',$filters);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
