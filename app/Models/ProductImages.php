<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['image_url'];


    public function getImageUrlAttribute(){
        return asset('storage/images/products/' . $this->image);
    }

    public function product(){
        return $this->belongsTo(product::class);
    }
}
