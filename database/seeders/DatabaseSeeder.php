<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\SeatSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\EmployeeSeeder;
use Database\Seeders\MenuCategorySeeder;
use Database\Seeders\EmployeeShiftSeeder;
use Database\Seeders\QuestionSeeder;
use Database\Seeders\QuestionCategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            AdminSeeder::class,
            CustomerSeeder::class,
            EmployeeSeeder::class,
            EmployeeShiftSeeder::class,
            SeatSeeder::class,
            QuestionCategorySeeder::class,
            QuestionSeeder::class,
            MenuCategorySeeder::class,
            MenuSeeder::class
        ]);
    }
}
