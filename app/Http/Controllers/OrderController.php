<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrder;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Shipping;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use HandleResponse;

    public function index()
    {
        $orders = Order::with('orderItems.product.images')
        ->where('user_id', Auth::id())
        ->get()
        ->map(function ($order) {
            $order->orderItems->map(function ($item) {
                if ($item->product && $item->product->images) {
                    $item->product->images = $item->product->images->map(function ($image) {
                        $image->image = asset('images/products/' . $image->image);
                        return $image;
                    });
                }
                return $item;
            });
            return $order;
        });    
        return $this->data(compact('orders'));
    }

    public function create(CreateOrder $req)
    {
        DB::transaction(function () use ($req){

            $user_id = Auth::id();
            $cart_items = Cart::with('product.images')->where('user_id', $user_id)->get();

            $sub_total = $cart_items->sum(fn($cart) => $cart->product->price * $cart->quantity);
            $discount = Discount::find($req->discount_id)->price ?? 0;
            $shipping = Shipping::find($req->shipping_id)?->price ?? 0;
            $total = max($sub_total - $discount + $shipping, 0);
            
            $order = Order::create([
                'first_name' => $req->first_name,
                'last_name' => $req->last_name,
                'email' => $req->email,
                'phone' => $req->phone,
                'address' => $req->address,
                'comment' => $req->comment,
                'user_id' => $user_id,
                'discount_id' => $req->discount_id,
                'shipping_id' => $req->shipping_id,
                'sub_total' => $sub_total,
                'total' => $total
            ]);

            
            $cart_items->each(function($cart) use ($order){
                OrderItems::create([
                    'product_id' => $cart->product->id,
                    'order_id' => $order->id,
                    'price' => $cart->product->price,
                    'quantity' => $cart->quantity
                ]);
            });

            Cart::where('user_id', $user_id)->delete();
        });
        return $this->successMessage('Created Successfully');
    }

    public function delete($id)
    {
        Order::where('user_id', Auth::id())->where('id', $id)->delete();
        
        return $this->successMessage('Deleted Successfully');
    }


}
