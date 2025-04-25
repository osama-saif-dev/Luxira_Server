<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasFactory, HasTranslations;
    
    protected $guarded = [];
    protected $appends = ['image_url', 'translatable_name'];
    protected $translatable = ['name'];

    public function getTranslatableNameAttribute()
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/images/brands/' . $this->image);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
