<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{

    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            Order::create([
                'id' => $i,
                'first_name' => "osama $i",
                'last_name' => "saif $i",
                'email' => "osamasaif24$i@gmail.com",
                'phone' => "3546586$i",
                'address' => 'qoutor',
                // 'total' => 150 * $i,
                'user_id' => $i,
                'discount_id' => $i,
                'shipping_id' => $i,
            ]);
        }
    }
}
