<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 5; $i++) {
            $discount_precentage =  5 + $i;
            Offer::create([
                'discount_percentage' => $discount_precentage,
                'discount_price' => 1000 - (1000 * $discount_precentage / 100),
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
                'product_id' => $i + 1
            ]);
        }
    }
}
