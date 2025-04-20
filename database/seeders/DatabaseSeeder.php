<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ProductImagesSeeder::class,
            OfferSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            ReviewSeeder::class,
            CartSeeder::class,
            WhishlisteSeeder::class,
            DiscountSeeder::class,
            ShippingSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
        ]);
    }
}
