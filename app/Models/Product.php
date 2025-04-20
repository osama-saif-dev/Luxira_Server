<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }

    public function offer(){
        return $this->hasOne(Offer::class);
    }

    public function images(){
        return $this->hasMany(ProductImages::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class, 'product_color')->withTimestamps();
    }

    public function sizes(){
        return $this->belongsToMany(Size::class, 'product_size')->withTimestamps();
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function whishlistes(){
        return $this->hasMany(Whishliste::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function orderItem(){
        return $this->hasMany(OrderItems::class);
    }
}
