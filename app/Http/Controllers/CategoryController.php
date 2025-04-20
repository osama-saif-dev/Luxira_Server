<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HandleResponse;

    public function index(){
        $categories = Category::all();
        foreach($categories as $category){
            $category->image_url = asset('images/categories/' . $category->image);
        }
        return $this->data(compact('categories'));
    }
    
}
