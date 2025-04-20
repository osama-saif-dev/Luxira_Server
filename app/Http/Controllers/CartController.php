<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCart;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use HandleResponse;

    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('product.images')
            ->get()
            ->map(function ($cart) {
                $cart->image_url = $cart->product->images->map(fn($image) => asset('images/products/' . $image->image));
                $cart->total = $cart->quantity * $cart->product->price;
                $cart->product->makeHidden(['images']);
                return $cart;
            });
        return $this->data(compact('cart'));
    }

    public function create(CreateCart $req)
    {
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $req->product_id,
            'quantity' => $req->quantity
        ]);
        return $this->successMessage('Created Successfully');
    }

    public function delete($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return $this->successMessage('Deleted Successfully');
    }
}
