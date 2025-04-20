<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{

    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            Brand::create([
                'id' => $i,
                'name' => 'Brand' . $i,
                'image' => 'default.jpg'
            ]);
        }
    }
}
