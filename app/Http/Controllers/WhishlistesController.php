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
            ->with([
                'product.images',
                'product.sizes',
                'product.colors'
            ])
            ->get();

        $whishlistes->each(function ($whishliste) {
            $whishliste->product?->makeHidden(['name', 'desc']);
            $whishliste->product?->sizes?->each->makeHidden(['size']);
            $whishliste->product?->colors?->each->makeHidden(['name']);
        });

        return $this->data(compact('whishlistes'));
    }

    public function store($id)
    {
        $user_id = Auth::id();
        $product_whishliste = Whishliste::where('user_id', $user_id)
                    ->where('product_id', $id)->first();
        if (!$product_whishliste)
        {
            Whishliste::create([
                'product_id' => $id,
                'user_id' => $user_id
            ]);
            return $this->successMessage(__('messages.create'));
        }
        return $this->errorsMessage(['error' => __('messages.product_whishlistes')]);
    }

    public function delete($id)
    {
        $user_id = Auth::user()->id;
        Whishliste::where('product_id', $id)->where('user_id', $user_id)->delete();
        return $this->successMessage(__('messages.delete'));
    }

}
