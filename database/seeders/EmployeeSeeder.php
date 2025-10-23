<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definisikan semua data karyawan dalam satu array
        $employeesData = [
            // Cooks
            ['name' => 'Cook1', 'position' => 'cook'],
            ['name' => 'Cook2', 'position' => 'cook'],
            ['name' => 'Cook3', 'position' => 'cook'],
            ['name' => 'Cook4', 'position' => 'cook'],

            // Waiters
            ['name' => 'Waiter1', 'position' => 'waiter'],
            ['name' => 'Waiter2', 'position' => 'waiter'],
            ['name' => 'Waiter3', 'position' => 'waiter'],
            ['name' => 'Waiter4', 'position' => 'waiter'],
            ['name' => 'Waiter5', 'position' => 'waiter'],
            ['name' => 'Waiter6', 'position' => 'waiter'],
            ['name' => 'Waiter7', 'position' => 'waiter'],

            // Bar
            ['name' => 'Bar1', 'position' => 'bar'],
            ['name' => 'Bar2', 'position' => 'bar'],

            // Office
            ['name' => 'Office1', 'position' => 'office'],
            ['name' => 'Office2', 'position' => 'office'],
        ];

        // Looping untuk membuat semua karyawan
        foreach ($employeesData as $employee) {
            Employee::create($employee);
        }
    }
}
