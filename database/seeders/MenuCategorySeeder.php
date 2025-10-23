<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuCategory;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuCategory::create([
            'name' => 'Belum ada Kategori'
        ]);

        MenuCategory::create([
            'name' => 'Makanan'
        ]);

        MenuCategory::create([
            'name' => 'Minuman'
        ]);

        MenuCategory::create([
            'name' => 'Dessert'
        ]);
    }
}
