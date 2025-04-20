<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Whishliste;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhishlistesController extends Controller
{
    use HandleResponse;

    public function index()
    {
        $user_id = Auth::id();
    
        $whishlistes = Whishliste::where('user_id', $user_id)
            ->with('product.images')
            ->get()
            ->each(function ($wishlist) {
                $wishlist->product->image_url = $wishlist->product->images->map(fn($image) => asset('images/products/' . $image->image));
                $wishlist->product->setHidden(['images']);
            });
    
        return $this->data(compact('whishlistes'));
    }

    public function create($id)
    {
        $user_id = Auth::user()->id;
        $whishlistes = Whishliste::where('product_id', $id)->where('user_id', $user_id)->first();
        if (!$whishlistes) {
            Whishliste::create([
                'user_id' => $user_id,
                'product_id' => $id
            ]);
            return $this->successMessage('Created Successfully');
        }
        return $this->successMessage('This Product Exists In Your Whishliste');
    }

    public function delete($id)
    {
        $user_id = Auth::user()->id;
        Whishliste::where('id', $id)->where('user_id', $user_id)->delete();
        return $this->successMessage('Deleted Successfully');
    }
}
