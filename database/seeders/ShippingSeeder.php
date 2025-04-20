<?php

namespace Database\Seeders;

use App\Models\Shipping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{

    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            Shipping::create([
                'id' => $i,
                'name' => "city $i",
                'price' => 120 * $i,
            ]);
        }
    }
}
