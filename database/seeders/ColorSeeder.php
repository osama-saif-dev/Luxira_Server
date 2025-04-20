<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{

    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            Color::create([
                'id' => $i,
                'name' => 'Color' . $i,
            ]);
        }
    }
}
