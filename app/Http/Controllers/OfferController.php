<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use HandleResponse;

    public function index(Request $request)
    {
        $offers = Offer::with('product.images')->get()
                    ->map(function ($offer) {
                        $product = $offer->product;
                        
                        $original_price = $product->price;
                        $discount = $offer->discount_percentage;
                        $offer->new_price = $original_price - ($original_price * $discount / 100);

                        $product->makeHidden(['name', 'desc']);
                        return $offer;
                    });
        return $this->data(compact('offers'));
    }

    public function cleanUp()
    {
        Offer::where('end_date', '<', now())->delete();
        return $this->successMessage(__('messages.delete_offer'));
    }
}
