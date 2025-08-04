<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrder;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\ProductCart;
use App\Models\Shipping;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Stripe;

use function PHPUnit\Framework\isEmpty;

class OrderController extends Controller
{
    use HandleResponse;

    public function index()
    {
        $orders = Order::with('orderItems.product.images')
            ->where('user_id', Auth::id())
            ->get()
            ->each(function ($order) {
            $order->orderItems->each(function ($item) {
                 $item->product->makeHidden(['desc', 'name']);
            });
        });    
        return $this->data(compact('orders'));
    }

    public function create(CreateOrder $req)
    {
        $user_id = Auth::id();
        $cart_items = ProductCart::with(['product', 'size', 'color'])->whereHas('cart', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->get();

        if (isEmpty($cart_items)) {
            return $this->errorsMessage(['error' => 'You mut have a cart']);
        }

        $sub_total = $cart_items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
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
            'total' => $total,
            'payment_method' => $req->payment_method
        ]);

        $cart_items->each(function ($item) use ($order) {
            OrderItems::create([
                'product_id' => $item->product_id,
                'order_id' => $order->id,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'size_id' => $item->size_id,
                'color_id' => $item->color_id,
            ]);
        });

        Cart::where('user_id', $user_id)->delete();

        // 1- PAYPAL
        if ($order->payment_method === 'paypal') {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypal_token = $provider->getAccessToken();

            $response = $provider->createOrder(
                [
                    "intent" => "CAPTURE",
                    "application_context" =>
                    [
                        // frontend_Url
                        "return_url" => url('https://clinic-client-m.vercel.app/my-appointments'),
                        "cancel_url" => url('https://clinic-client-m.vercel.app/'),
                    ],
                    "purchase_units" =>
                    [
                        [
                            "amount" =>
                            [
                                "currency_code" => "USD",
                                "value" => $order->total, 
                            ],
                            "custom_id" => $order->id,
                        ]
                    ]
                ]
            );
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return $this->data([
                            'status' => 'success',
                            'order_id' => $response['id'],
                            'approval_url' => $link['href'],
                        ]);
                    }
                }
            }
        }

        // 2- STRIPE
        else if ($order->payment_method === 'stripe') {

            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Reservation'
                        ],
                        'unit_amount' => $order->total * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'https://clinic-client-m.vercel.app/my-appointments?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'https://clinic-client-m.vercel.app/cancel',
            ]);

            $order->transaction_id = $session->id;
            $order->payment_method = 'stripe';
            $order->save();

            return response()->json(['sessionId' => $session->id]);
        }

        // 3- Cache
        else {
            return $this->successMessage('Payment Successfully');
        }
    }

    public function delete($id)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$order) return $this->errorsMessage(['error' => 'Order not found']);
        OrderItems::where('order_id', $order->id)->delete();
        $order->delete();
        return $this->successMessage(__('messages.delete'));
    }
}
