<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HandleResponse;

    public function index(Request $request)
    {
        $categories = Category::where('status', 'active')->get()
                ->map(function($cat){
                    $cat->makeHidden(['name']);
                    return $cat;
                });
        return $this->data(compact('categories'));
    }
    
}
