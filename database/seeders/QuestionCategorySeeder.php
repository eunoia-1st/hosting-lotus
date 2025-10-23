<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionCategory;

class QuestionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            /* 1 */
            ['name' => 'Pelayanan', 'status' => 'pending'],
            /* 2 */
            ['name' => 'Kebersihan', 'status' => 'pending'],
            /* 3 */
            ['name' => 'Suasana', 'status' => 'pending'],
            /* 4 */
            ['name' => 'Menu', 'status' => 'inactive'],
            /* 5 */
            ['name' => 'Kemudahan', 'status' => 'inactive'],
            /* 6 */
            ['name' => 'Keseluruhan', 'status' => 'inactive'],
            /* 7 */
            ['name' => 'Harga', 'status' => 'inactive'],
        ];

        foreach ($categories as $category) {
            QuestionCategory::create($category);
        }
    }
}
