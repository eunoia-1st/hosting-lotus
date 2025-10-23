<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seat::create([
            'name' => '5A'
        ]);

        Seat::create([
            'name' => '3'
        ]);

        Seat::create([
            'name' => '1'
        ]);
        Seat::create([
            'name' => '2'
        ]);
        Seat::create([
            'name' => '5'
        ]);
        Seat::create([
            'name' => '6'
        ]);
        Seat::create([
            'name' => '7'
        ]);
        Seat::create([
            'name' => '8'
        ]);
        Seat::create([
            'name' => '9'
        ]);
        Seat::create([
            'name' => '10'
        ]);
        Seat::create([
            'name' => '14'
        ]);
        Seat::create([
            'name' => '15'
        ]);
        Seat::create([
            'name' => '16'
        ]);
        Seat::create([
            'name' => '17'
        ]);
        Seat::create([
            'name' => '18'
        ]);
        Seat::create([
            'name' => '19'
        ]);
        Seat::create([
            'name' => '20'
        ]);
        Seat::create([
            'name' => '21'
        ]);
        Seat::create([
            'name' => '22'
        ]);
        Seat::create([
            'name' => '23'
        ]);
    }
}
