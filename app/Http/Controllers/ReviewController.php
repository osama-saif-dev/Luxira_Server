<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReview;
use App\Models\Review;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use HandleResponse;

    public function store(StoreReview $req)
    {
        $user_id = Auth::id();
        $review = Review::create([
            'comment' => $req->comment,
            'rate' => $req->rate,
            'product_id' => $req->product_id,
            'user_id' => $user_id
        ]);
        return $this->data(compact('review'), __('messages.send_message'));
    }

    public function update(StoreReview $req, $id)
    {
        $user_id = Auth::id();
        $review = Review::where('id', $id)->where('user_id', $user_id)->first();
        if ($review) {
            $review->update([
                'comment' => $req->comment,
                'rate' => $req->rate,
                'product_id' => $req->product_id,
                'user_id' => $user_id
            ]);
            return $this->successMessage(__('messages.update'));
        }
    }

    public function delete($id)
    {
        Review::findOrFail($id)->delete();
        return $this->successMessage(__('messages.delete'));
    }
}
