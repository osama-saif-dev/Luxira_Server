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
        $all = filter_var($request->query('all'), FILTER_VALIDATE_BOOLEAN);

        $offers = Offer::with('product.images', 'product.sizes', 'product.colors')
            ->when(!$all, fn($query) => $query->take(4))
            ->get();

        $offers->each(function ($offer) {
            $product = $offer->product;

            $offer->new_price = $product->price - ($product->price * $offer->discount_percentage / 100);
            $product->sizes->makeHidden(['size']);
            $product->makeHidden(['name', 'desc']);
        });

        return $this->data(compact('offers'));
    }

    public function cleanUp()
    {
        Offer::where('end_date', '<', now())->delete();
        return $this->successMessage(__('messages.delete_offer'));
    }
}
