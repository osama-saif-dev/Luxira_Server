<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use HandleResponse;

    public function index(){
        $offers = Offer::all();
        return $this->data(compact('offers'));
    }
}
