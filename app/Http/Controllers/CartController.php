<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCart;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use HandleResponse;

    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart) {
                return $this->data([
                    'cart_products' => [],
                    'total_price' => 0
                ]);
            }
        $cart_products = ProductCart::with([
            'product.images',
            'size',
            'color',
        ])->where('cart_id', $cart->id)->get()
            ->map(function ($q) {
                $q->product->makeHidden(['name', 'desc']);
                $q->size?->makeHidden(['size']);
                $q->color?->makeHidden(['name']);
                $q['total_price'] = $q->quantity * $q->product->price;
                return $q;
            });
        $total_price = $cart_products->sum('total_price');
        return $this->data(compact('cart_products', 'total_price'));
    }

    public function store(CreateCart $req)
    {
        $user_id = Auth::id();

        $cart = Cart::where('user_id', $user_id)->first();
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user_id]);
        }

        $product = Product::find($req->product_id);

        $cart_product = ProductCart::where('cart_id', $cart->id)
            ->where('color_id', $req->color_id)
            ->where('size_id', $req->size_id)
            ->where('product_id', $req->product_id)
            ->first();

        if (!$cart_product) {
            if ($req->quantity <= $product->quantity) {
                $newProduct = ProductCart::create([
                    'cart_id' => $cart->id,
                    'product_id' => $req->product_id,
                    'color_id' => $req->color_id,
                    'size_id' => $req->size_id,
                    'quantity' => $req->quantity,
                ]);

                return $this->data(compact('newProduct'), __('messages.create'));
            } else {
                return $this->errorsMessage(['This Quantity is Not Allowed']);
            }
        } else {
            $cart_product->update([
                'quantity' => $req->quantity,
                'color_id' => $req->color_id,
                'size_id' => $req->size_id,
            ]);
            return $this->data(compact('cart_product'), __('messages.update'));
        }
    }

    public function deleteCart($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return $this->successMessage(__('messages.delete'));
    }

    public function deleteProduct($id)
    {
        $cart = Cart::where('user_id', Auth::id());
        ProductCart::where('cart_id', $cart->id)->where('product_id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }
}
