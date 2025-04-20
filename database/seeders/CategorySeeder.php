<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            Category::create([
                'id' => $i,
                'name' => 'Category' . $i,
                'image' => 'default.jpg'
            ]);
        }
    }
}
