<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['pivot'];

    
    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(product::class, 'product_sizes');
    }

}
