<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Size;
use App\Models\Subcategory;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use HandleResponse;

    public function index()
    {
        $subcategories = Subcategory::all();
        $categories = Category::all();
        $brands = Brand::all();
        $colors = Color::all();
        $sizes = Size::all();
        return $this->data(compact('categories', 'subcategories', 'colors', 'sizes'));
    }

    public function filter(Request $req) 
    {
        $products = Product::query()
            ->when($req->search, fn($q) => $q->where('name', 'LIKE', "%{$req->search}%"))
            ->when(
                $req->category_id,
                fn($q) =>
                $q->whereHas('subcategory.category', fn($q) => $q->where('id', $req->category_id))
            )
            ->when($req->subcategory_id, fn($q) => $q->where('subcategory_id', $req->subcategory_id))
            ->when($req->brand_id, fn($q) => $q->where('brand_id', $req->brand_id))
            ->when(
                $req->min_price && $req->max_price,
                fn($q) =>
                $q->whereBetween('price', [$req->min_price, $req->max_price])
            )
            ->when(
                $req->color_id,
                fn($q) =>
                $q->whereHas('colors', fn($q) => $q->where('colors.id', $req->color_id))
            )
            ->when(
                $req->size_id,
                fn($q) =>
                $q->whereHas('sizes', fn($q) => $q->where('sizes.id', $req->size_id))
            )
            ->where('status', 'active')
            ->with(['images', 'colors', 'sizes'])
            ->withAvg('reviews', 'rate')
            ->paginate(12);

        $products->each(function ($product) {
            $product->reviews_avg_rate = round($product->reviews_avg_rate) + 0;
            $product->makeHidden(['reviews','desc','name']);
        });

        $total_pages = $products->lastPage();
        $products = $products->items();

        return $this->data(compact('products', 'total_pages'));
    }

    public function show(Request $req, $id)
    {
        $product = Product::where('id', $id)
        ->with([
            'colors',
            'sizes',
            'images',
            'reviews',
            'reviews.user:id,first_name,last_name,email,image'
        ])
        ->withAvg('reviews', 'rate') 
        ->firstOrFail();

        // change to integer  
        $product->reviews_avg_rate = round($product->reviews_avg_rate) + 0;

        $similar_products_query = Product::where('subcategory_id', $product->subcategory_id)
        ->where('id', '!=', $product->id)
        ->with('images', 'sizes', 'colors')
        ->where('status', 'active')
        ->withAvg('reviews', 'rate')
        ->paginate(1);

        $similar_products_query->each(fn ($product) => $product->reviews_avg_rate = round($product->reviews_avg_rate) + 0);
        
        $total_pages = $similar_products_query->lastPage();
        $similar_products = $similar_products_query->items();

        collect($similar_products)->each(function ($product) {
            $product->makeHidden(['name', 'desc']);
        });
        $product->colors->makeHidden(['pivot']); 
        $product->sizes->makeHidden(['pivot','size']); 

        return $this->data(compact('product', 'similar_products', 'total_pages'));
    }
}
