<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use HandleResponse;

    public function getBrands()
    {
        $brands = Brand::where('status', 'active')->get();
        return $this->data(compact('brands'));
    }
}
