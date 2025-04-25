<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use HandleResponse;

    public function index(Request $request)
    {
        $brands = Brand::where('status', 'active')->get()
                ->map(function($brand){
                    $brand->makeHidden(['name']);
                    return $brand;
                });
        return $this->data(compact('brands'));
    }
}
