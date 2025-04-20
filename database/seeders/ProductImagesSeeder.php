<?php

namespace Database\Seeders;

use App\Models\ProductImages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{

    public function run(): void
    {
        for ($i = 1; $i < 15; $i++) {
            ProductImages::create([
                'id' => $i,
                'image' => 'default.jpg',
                'product_id' => $i
            ]);
        }
    }
}
