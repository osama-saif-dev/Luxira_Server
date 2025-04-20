<?php

namespace Database\Seeders;

use App\Models\Whishliste;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhishlisteSeeder extends Seeder
{

    public function run(): void
    {
        for ($i = 1; $i < 15; $i++) {
            Whishliste::create([
                'id' => $i,
                'user_id' => $i,
                'product_id' => $i
            ]);
        }
    }
}
