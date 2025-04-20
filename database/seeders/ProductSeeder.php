<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 15; $i++) {
            Product::create([
                'id' => $i,
                'name' => 'Product' . $i,
                'price' => 150 * $i,
                'quantity' => $i * 2,
                'desc' => "This Is My Desc $i",
                'brand_id' => $i,
                'subcategory_id' => $i
            ]);
        }
    }
}
