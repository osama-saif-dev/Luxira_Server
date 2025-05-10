<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrand;
use App\Http\Requests\StoreCategory;
use App\Http\Requests\StoreColor;
use App\Http\Requests\StoreDiscount;
use App\Http\Requests\StoreOffer;
use App\Http\Requests\StoreProduct;
use App\Http\Requests\StoreShipping;
use App\Http\Requests\StoreSize;
use App\Http\Requests\StoreSubcategory;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateBrand;
use App\Http\Requests\UpdateCategory;
use App\Http\Requests\UpdateProduct;
use App\Http\Requests\UpdateSubcategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Discount;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImages;
use App\Models\ProductSize;
use App\Models\Shipping;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;
use App\Traits\HandleResponse;
use App\Traits\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    use HandleResponse, Model;

    ################################ Users ################################

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
            'role' => $request->role,
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

    public function showUser($id)
    {
        $user = User::with(['reviews', 'orders', 'cart.products'])->findOrFail($id);
        return $this->data(compact('user'));
    }

    public function deleteUser($id)
    {
        User::where('id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }



    ################################ Brands ################################

    public function getBrands()
    {
        $brands = Brand::all();
        return $this->data(compact('brands'));
    }

    public function storeBrand(StoreBrand $request)
    {
        $photoName = $this->storeImage($request->image, 'brands');
        $brand = new Brand();
        $brand->image = $photoName;
        $brand->status = $request->status;
        $brand->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar
        ]);
        $brand->save();
        return $this->successMessage(__('messages.create'), 201);
    }

    public function updateBrand(UpdateBrand $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $data = $request->except('image', 'name_en', 'name_ar');

        if ($request->hasFile('image')) {
            // delete old photo
            Storage::disk('public')->delete('images/brands/' . $brand->image);
            // store new photo
            $photoName = $this->storeImage($request->image, 'brands');
            $data['image'] = $photoName;
        }

        $brand->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar
        ]);

        $brand->update($data);

        return $this->successMessage(__('messages.update'));
    }

    public function showBrand(Request $request, $id)
    {
        $locale = $request->header('Accept-Language');
        $brand = Brand::findOrFail($id);
        return $this->data(compact('brand'));
    }

    public function deleteBrand($id)
    {
        Brand::find($id)->delete();
        return $this->successMessage(__('messages.delete'));
    }

    ################################ Categories ################################

    public function getCategories()
    {
        $categories = Category::all();
        return $this->data(compact('categories'));
    }

    public function storeCategory(StoreCategory $request)
    {
        $photoName = $this->storeImage($request->image, 'categories');
        $category = new Category();
        $category->image = $photoName;
        $category->status = $request->status;
        $category->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar
        ]);
        $category->save();
        return $this->successMessage(__('messages.create'), 201);
    }

    public function updateCategory(UpdateCategory $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->except('image', 'name_en', 'name_ar');

        if ($request->hasFile('image')) {
            // delete old photo
            Storage::disk('public')->delete('images/categories/' . $category->image);
            // store new photo
            $photoName = $this->storeImage($request->file('image'), 'categories');
            $data['image'] = $photoName;
        }

        $category->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar
        ]);

        $category->update($data);

        return $this->successMessage(__('messages.update'));
    }

    public function showCategory($id)
    {
        $category = Category::findOrFail($id);
        return $this->data(compact('category'));
    }

    public function deleteCategory($id)
    {

        Category::find($id)->delete();
        return $this->successMessage(__('messages.delete'));
    }

    ################################ Subcategories ################################

    public function getSubcategories()
    {
        $subcategories = Subcategory::all();
        return $this->data(compact('subcategories'));
    }

    public function storeSubcategory(StoreSubcategory $request)
    {
        $photoName = $this->storeImage($request->file('image'), 'subcategories');
        $subcategory = new Subcategory();
        $subcategory->image = $photoName;
        $subcategory->status = $request->status;
        $subcategory->category_id = $request->category_id;
        $subcategory->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar
        ]);
        $subcategory->save();
        return $this->successMessage(__('messages.create'), 201);
    }

    public function updateSubcategory(UpdateSubcategory $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $data = $request->except('image', 'name_en', 'name_ar');

        if ($request->hasFile('image')) {
            // delete old photo
            Storage::disk('public')->delete('images/subcategories/' . $subcategory->image);
            // store new photo
            $photoName = $this->storeImage($request->file('image'), 'subcategories');
            $data['image'] = $photoName;
        }

        $subcategory->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar
        ]);

        $subcategory->update($data);
        return $this->successMessage(__('messages.update'));
    }

    public function showSubcategory($id)
    {
        $subcategory = Subcategory::find($id);
        return $this->data(compact('subcategory'));
    }


    public function deleteSubcategory($id)
    {

        Subcategory::find($id)->delete();
        return $this->successMessage(__('messages.delete'));
    }



    ################################ Products ################################

    public function getProducts()
    {
        $products = Product::with(
            [
                'sizes:id,size',
                'colors:id,name',
                'images',
                'brand:id,name',
                'subcategory.category'
            ]
        )->get();
        return $this->data(compact('products'));
    }

    public function showProduct($id)
    {
        $product = Product::with(
            [
                'sizes:id,size',
                'colors:id,name',
                'images',
                'brand:id,name',
                'subcategory.category'
            ]
        )->findOrFail($id);
        return $this->data(compact('product'));
    }

    public function getDataToProduct()
    {
        $subcategories = Subcategory::all()->makeHidden('name');
        $brands = Brand::all()->makeHidden('name');
        $colors = Color::all()->makeHidden('name');
        $sizes = Size::all()->makeHidden('size');
        return $this->data(compact('subcategories', 'brands', 'colors', 'sizes'));
    }

    public function storeProduct(StoreProduct $request)
    {
        return DB::transaction(function () use ($request) {

            $data = $request->except('image', 'size_id', 'color_id', 'name_en', 'name_ar', 'desc_en', 'desc_ar');
            $data['name'] = [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ];
            $data['desc'] = [
                'en' => $request->desc_en,
                'ar' => $request->desc_ar,
            ];
            $product = Product::create($data);

            // sync بتحذف العلاقات sizes , colors حتى لو array القديمه وتضيف الجديد 
            $product->sizes()->sync($request->size_id);
            $product->colors()->sync($request->color_id);

            if ($request->hasFile('image')) {
                foreach ($request->image as $image) {
                    $photoName = $this->storeImage($image, 'products');
                    ProductImages::create([
                        'image' => $photoName,
                        'product_id' => $product->id
                    ]);
                }
            }

            return $this->successMessage(__('messages.create'), 201);
        });
    }

    public function updateProduct(UpdateProduct $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {

            $product = Product::findOrFail($id);
            $data = $request->except('image', 'size_id', 'color_id', 'name_en', 'name_ar', 'desc_en', 'desc_ar');
            $data['name'] = [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ];
            $data['desc'] = [
                'en' => $request->desc_en,
                'ar' => $request->desc_ar,
            ];
            $product->update($data);

            // sync بتحذف العلاقات sizes , colors حتى لو array القديمه وتضيف الجديد 
            $product->sizes()->sync($request->size_id);
            $product->colors()->sync($request->color_id);

            if ($request->hasFile('image')) {
                foreach ($request->image as $image) {
                    $photoName = $this->storeImage($image, 'products');
                    ProductImages::create([
                        'image' => $photoName,
                        'product_id' => $product->id
                    ]);
                }
            }

            return $this->successMessage(__('messages.update'), 201);
        });
    }

    public function deleteImage($productId, $imageId)
    {
        $image_count = ProductImages::where('product_id', $productId)->count();
        if ($image_count > 1) {
            $product_images = ProductImages::where('product_id', $productId)
                ->where('id', $imageId)->first();
            $product_images->delete();
            Storage::disk('public')->delete('images/products/' . $product_images->image);
            return $this->successMessage(__('messages.delete'));
        }
        return $this->errorsMessage(['error' => __('messages.cannot_delete_last_image')]);
    }


    public function deleteProduct($id)
    {

        Product::find($id)->delete();
        return $this->successMessage(__('messages.delete'));
    }


    
    ################################ Offers ################################

    public function getOffers()
    {
        $offers = Offer::all();
        return $this->data(compact('offers'));
    }

    public function showOffer($id)
    {
        $offer = Offer::findOrFail($id);
        return $this->data(compact('offer'));
    }

    public function storeOffer(StoreOffer $request)
    {
        $data = $request->validated();
        Offer::create($data);
        return $this->successMessage(__('messages.create'), 201);
    }

    public function updateOffer(StoreOffer $request, $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->update($request->validated());
        return $this->successMessage(__('messages.update'));
    }

    public function deleteOffer($id)
    {
        Offer::where('id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }


    ################################ Sizes ################################

    public function getSizes()
    {
        $sizes = Size::all();
        return $this->data(compact('sizes'));
    }

    public function showSize($id)
    {
        $size = Size::findOrFail($id);
        return $this->data(compact('size'));
    }

    public function storeSize(StoreSize $request)
    {
        $size = new Size();
        $size->setTranslations('size', [
            'en' => $request->size_en,
            'ar' => $request->size_ar,
        ]);
        $size->save();
        return $this->successMessage(__('messages.create'), 201);
    }

    public function deleteSize($id)
    {
        Size::where('id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }


    ################################ Colors ################################

    public function getColors()
    {
        $colors = Color::all();
        return $this->data(compact('colors'));
    }

    public function showColor($id)
    {
        $color = Color::findOrFail($id);
        return $this->data(compact('color'));
    }

    public function storeColor(StoreColor $request)
    {
        $color = new Color();
        $color->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar,
        ]);
        $color->save();
        return $this->successMessage(__('messages.create'), 201);
    }

    public function deleteColor($id)
    {
        Color::where('id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }



    ################################ Discounts ################################

    public function getDiscounts()
    {
        $discounts = Discount::all();
        return $this->data(compact('discounts'));
    }

    public function showDiscount($id)
    {
        $discount = Discount::findOrFail($id);
        return $this->data(compact('discount'));
    }

    public function storeDiscount(StoreDiscount $request)
    {
        Discount::create($request->validated());
        return $this->successMessage(__('messages.create'), 201);
    }

    public function updateDiscount(StoreDiscount $request, $id)
    {
        $discount = Discount::findOrFail($id);
        $discount->update($request->validated());
        return $this->successMessage(__('messages.update'));
    }

    public function deleteDiscount($id)
    {
        Discount::where('id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }



    ################################ Shippings ################################

    public function getShippings()
    {
        $shippings = Shipping::all();
        return $this->data(compact('shippings'));
    }

    public function showShipping($id)
    {
        $shipping = Shipping::findOrFail($id);
        return $this->data(compact('shipping'));
    }

    public function storeShipping(StoreShipping $request)
    {
        $shipping = new Shipping();
        $shipping->setTranslations('city', [
            'en' => $request->city_en,
            'ar' => $request->city_ar,
        ]);
        $shipping->price = $request->price;
        $shipping->save();
        return $this->successMessage(__('messages.create'), 201);
    }

    public function updateShipping(StoreShipping $request, $id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->update($request->validated());
        return $this->successMessage(__('messages.update'));
    }

    public function deleteShipping($id)
    {
        Shipping::where('id', $id)->delete();
        return $this->successMessage(__('messages.delete'));
    }
}
