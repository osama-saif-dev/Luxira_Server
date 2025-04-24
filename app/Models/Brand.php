<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return asset('storage/images/brands/' . $this->image);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
