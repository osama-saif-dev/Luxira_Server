<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct;
use App\Http\Requests\StoreUser;
use App\Models\Product;
use App\Models\User;
use App\Traits\HandleResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use HandleResponse;

    ###### Users ######

    public function getUsers()
    {
        $users = User::where('role', 'user')->get();
        return $this->data(compact('users'));
    }

    public function storeUser(StoreUser $request)
    {
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'password' => $request->password
        ]);
        return $this->successMessage(__('messages.create'), 201);
    }
    
    public function changeRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|in:admin,user'
        ]);
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        return $this->successMessage(__('messages.update'));
    }

    public function deleteUser($id)
    {
        User::where('id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }


    ###### Products ######

    public function getProducts()
    {
        $products = DB::table('products')->select('*')->get();
        return $this->data(compact('products'));
    }

    public function storeProduct(StoreProduct $request)
    {
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'desc' => $request->desc,
            'status' => $request->status,
            'brand_id' => $request->brand_id,
            'subcategory_id' => $request->subcategory_id
        ]);
        return $this->successMessage(__('messages.create'), 201);
    }

    public function updateProduct(StoreProduct $request)
    {
        Product::update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'desc' => $request->desc,
            'status' => $request->status,
            'brand_id' => $request->brand_id,
            'subcategory_id' => $request->subcategory_id
        ]);
        return $this->successMessage(__('messages.update'), 201);
    }

    public function changeProductStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:active,un_active'
        ]);
        $product = Product::findOrFail($id);
        $product->status = $request->status;
        $product->save();
        return $this->successMessage(__('messages.update'));
    }

}
