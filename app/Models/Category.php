<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return asset('storage/images/categories/' . $this->image);
    }

    public function subcategories(){
        return $this->hasMany(Subcategory::class);
    }
}
