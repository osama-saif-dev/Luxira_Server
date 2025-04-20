<?php

namespace Database\Seeders;

use App\Models\OrderItems;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{

    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            OrderItems::create([
                'id' => $i,
                'price' => $i + 10,
                'quantity' => $i,
                'product_id' => $i,
                'order_id' => $i
            ]);
        }
    }
}
