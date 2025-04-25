<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Size extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];
    protected $hidden = ['pivot'];
    protected $appends = ['translatable_size'];
    protected $translatable = ['size'];

    public function getTranslatableSizeAttribute() 
    {
        return $this->getTranslation('size', app()->getLocale());
    }

    public function sizes()
    {
        return $this->belongsToMany(product::class, 'product_sizes');
    }

}
