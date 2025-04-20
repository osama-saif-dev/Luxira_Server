<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            Discount::create([
                'id' => $i,
                'code' => "3455$i",
                'code_expired_at' => now()->addMinutes(3),
                'price' => 120 * $i,
            ]);
        }
    }
}
