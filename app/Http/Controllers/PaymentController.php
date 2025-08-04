<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{
    use HandleResponse;

    public function successPaypal(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->query('token'));

        if (isset($response['purchase_units'][0]['payments']['captures'][0]['custom_id'])) {
            $order_id = $response['purchase_units'][0]['payments']['captures'][0]['custom_id'];
            $order = Order::where('id', $order_id)->with('appointment')->first();
            if (!$order) {
                return $this->errorsMessage(['error' => 'Order not found']);
            }

            $order->is_paid = 'paid';
            $order->transaction_id = $response['id'];
            $order->save();

            return $this->data([
                'message' => 'Payment successful',
                'status' => $response['status']
            ], 200);
        } else {
            return $this->errorsMessage(['error' => 'custom_id not found in PayPal response']);
        }
    }

    public function successStripe(Request $request)
    {
        // انا حافظه ف ال services => config
        Stripe::setApiKey(config('services.stripe.secret'));
        // استرجاع session_id من الطلب
        $session_id = $request->query('session_id');
        if (!$session_id) {
            return $this->errorsMessage(['message' => 'Transaction ID not found']);
        }
        // جلب تفاصيل الجلسة من Stripe
        $session = Session::retrieve($session_id);

        // البحث عن الحجز بناءً على transaction_id
        $order = Order::where('transaction_id', $session_id)->first();
        if (!$order) {
            return $this->errorsMessage(['message' => 'Order not found']);
        }

        // التحقق من حالة الدفع في Stripe
        if ($session->payment_status === 'paid') {
            // تحديث الحجز كمدفوع
            $order->is_paid = 'paid';
            $order->save();

            return $this->data([
                'message' => 'Payment successful',
                'status' => 'COMPLETED',
            ], 200);
        } else {
            // إذا لم يتم الدفع، يتم حذف الحجز
            $order->delete();
            return $this->errorsMessage([
                'message' => 'Payment failed, reservation deleted',
                'status' => 'FAILED',
            ], 400);
        }
    }
}
