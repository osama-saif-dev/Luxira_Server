<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{

    public function run(): void
    {
        for ($i = 1; $i < 15; $i++) {
            Cart::create([
                'id' => $i,
                'user_id' => $i,
                'product_id' => $i,
            ]);
        }
    }
}
