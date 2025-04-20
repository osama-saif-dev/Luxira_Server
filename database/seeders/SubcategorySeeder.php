<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i < 15; $i++){
            Subcategory::create([
                'id' => $i,
                'name' => 'Subcategory' . $i,
                'image' => 'default.jpg',
                'category_id' => $i
            ]);
        }
    }
}
