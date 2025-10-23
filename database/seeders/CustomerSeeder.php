<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Customer 1',
            'email' => '',
            'phone' => '0811237631',
            'address' => 'jl. Mawar no. 22'
        ]);

        Customer::create([
            'name' => 'Customer 2',
            'email' => 'customer2@gmail.com',
            'phone' => '',
            'address' => 'jl. Penyu no. 19'
        ]);

        Customer::create([
            'name' => 'Customer 3',
            'email' => 'customer3@gmail.com',
            'phone' => '0983834193',
            'address' => 'jl. Singa no. 02'
        ]);
    }
}
