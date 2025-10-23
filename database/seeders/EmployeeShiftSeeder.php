<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EmployeeShift;

class EmployeeShiftSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil semua karyawan sekali saja untuk efisiensi
        $employees = Employee::all()->keyBy('name');

        // 2. Definisikan semua jadwal di satu tempat
        $schedules = [
            'Cook1'   => ['default' => ['type' => 'morning', 'start' => '09:00', 'end' => '17:00'], 'overrides' => ['saturday' => ['type' => 'off'], 'sunday' => ['type' => 'off']]],
            'Cook2'   => ['default' => ['type' => 'morning', 'start' => '09:00', 'end' => '17:00'], 'overrides' => ['monday' => ['type' => 'off'], 'tuesday' => ['type' => 'off']]],
            'Cook3'   => ['default' => ['type' => 'evening', 'start' => '15:00', 'end' => '23:00'], 'overrides' => ['wednesday' => ['type' => 'off'], 'thursday' => ['type' => 'off']]],
            'Cook4'   => ['default' => ['type' => 'evening', 'start' => '15:00', 'end' => '23:00']],
            'Waiter1' => ['default' => ['type' => 'split', 'start1' => '10:00', 'end1' => '14:00', 'start2' => '19:00', 'end2' => '23:00'], 'overrides' => ['sunday' => ['type' => 'off']]],
            'Waiter2' => ['default' => ['type' => 'split', 'start1' => '10:00', 'end1' => '14:00', 'start2' => '19:00', 'end2' => '23:00'], 'overrides' => ['monday' => ['type' => 'off']]],
            'Waiter3' => ['default' => ['type' => 'morning', 'start' => '09:00', 'end' => '17:00']],
            'Waiter4' => ['default' => ['type' => 'morning', 'start' => '09:00', 'end' => '17:00']],
            'Waiter5' => ['default' => ['type' => 'evening', 'start' => '15:00', 'end' => '23:00']],
            'Waiter6' => ['default' => ['type' => 'evening', 'start' => '15:00', 'end' => '23:00']],
            'Waiter7' => ['default' => ['type' => 'evening', 'start' => '15:00', 'end' => '23:00']],
            'Bar1'    => ['default' => ['type' => 'split', 'start1' => '11:00', 'end1' => '15:00', 'start2' => '18:00', 'end2' => '23:00'], 'overrides' => ['tuesday' => ['type' => 'off']]],
            'Bar2'    => ['default' => ['type' => 'evening', 'start' => '16:00', 'end' => '23:59']],
            'Office1' => ['default' => ['type' => 'morning', 'start' => '09:00', 'end' => '17:00'], 'overrides' => ['saturday' => ['type' => 'off'], 'sunday' => ['type' => 'off']]],
            'Office2' => ['default' => ['type' => 'morning', 'start' => '09:00', 'end' => '17:00'], 'overrides' => ['saturday' => ['type' => 'off'], 'sunday' => ['type' => 'off']]],
        ];

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        // 3. Looping data jadwal untuk membuat shift
        foreach ($schedules as $employeeName => $schedule) {
            if (!isset($employees[$employeeName])) {
                continue; // Lanjut jika nama karyawan di jadwal tidak ada di database
            }
            $employee = $employees[$employeeName];

            foreach ($days as $day) {
                $daySchedule = $schedule['overrides'][$day] ?? $schedule['default'];

                $shiftData = [
                    'employee_id' => $employee->id,
                    'day' => $day,
                    'shift_type' => null,
                    'start_time' => null,
                    'end_time' => null,
                    'start_time_2' => null,
                    'end_time_2' => null,
                ];

                switch ($daySchedule['type']) {
                    case 'split':
                        $shiftData['shift_type'] = 'split';
                        $shiftData['start_time'] = $daySchedule['start1'];
                        $shiftData['end_time'] = $daySchedule['end1'];
                        $shiftData['start_time_2'] = $daySchedule['start2'];
                        $shiftData['end_time_2'] = $daySchedule['end2'];
                        break;
                    case 'morning':
                    case 'evening':
                    case 'middle':
                        $shiftData['shift_type'] = $daySchedule['type'];
                        $shiftData['start_time'] = $daySchedule['start'];
                        $shiftData['end_time'] = $daySchedule['end'];
                        break;
                }
                EmployeeShift::create($shiftData);
            }
        }
    }
}
