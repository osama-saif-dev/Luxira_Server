<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Color extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_colors');
    }

}
