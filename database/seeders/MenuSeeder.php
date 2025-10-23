<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'name' => 'Nasi Goreng Lotus',
            'price' => 40000,
            'menu_category_id' => 1  // Belum ada Kategori
        ]);

        Menu::create([
            'name' => 'Sup Buntut',
            'price' => 40000,
            'menu_category_id' => 2  // Makanan
        ]);

        Menu::create([
            'name' => 'Es Jeruk',
            'price' => 40000,
            'menu_category_id' => 3  // Minuman
        ]);

        Menu::create([
            'name' => 'Puding Busa',
            'price' => 34000,
            'menu_category_id' => 4  // Dessert
        ]);
    }
}
