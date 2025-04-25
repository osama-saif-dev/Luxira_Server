<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    use HandleResponse;

    public function index(Request $request)
    {
        $subcategories = Subcategory::where('status', 'active')->get()
                ->map(function($subcategory){
                    $subcategory->makeHidden(['name']);
                    return $subcategory;
                });
        return $this->data(compact('subcategories'));
    }
    
}
