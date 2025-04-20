<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{

    public function run(): void
    {
        for ($i = 1; $i < 15; $i++) {
            Review::create([
                'id' => $i,
                'comment' => 'Comment' . $i,
                'rate' => 4 * $i,
                'product_id' => $i,
                'user_id' => $i
            ]);
        }
    }
}
