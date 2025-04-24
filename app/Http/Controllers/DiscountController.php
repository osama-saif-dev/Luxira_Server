<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    use HandleResponse;

    public function cleanUp()
    {
        Discount::where('end_date', '<', now())->delete();
        return $this->successMessage(__('messages.delete_offer'));
    }
    
}
