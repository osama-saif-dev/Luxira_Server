<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Shipping extends Model
{
    use HasFactory, HasTranslations;
    
    protected $guarded = [];
    protected $translatable = ['city'];

    public function orders(){
        return $this->hasMany(Order::class);
    }

}
