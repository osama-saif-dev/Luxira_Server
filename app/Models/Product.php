<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['pivot'];
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function whishlistes()
    {
        return $this->hasMany(Whishliste::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'product_carts');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItems::class);
    }
}
