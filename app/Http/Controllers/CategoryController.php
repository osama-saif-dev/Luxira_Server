<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HandleResponse;

    public function index(){
        $categories = Category::where('status', 'active')->get();
        return $this->data(compact('categories'));
    }
    
}
