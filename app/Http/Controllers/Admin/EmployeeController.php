<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeShift; // Pastikan model EmployeeShift di-import jika belum

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with('employee_shifts');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $employees = $query->get();
        // Menambahkan 'bar' ke daftar posisi agar konsisten
        $positions = ['office', 'cook', 'waiter', 'staff', 'bar'];

        return view('employees.index', compact('employees', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employee = Employee::create([
            'name' => $request->name,
            'position' => $request->position
        ]);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            // PERUBAHAN DI SINI: Inisialisasi kolom split shift saat karyawan baru dibuat
            $employee->employee_shifts()->create([
                'day' => $day,
                'start_time' => null,
                'end_time' => null,
                'start_time_2' => null, // Ditambahkan
                'end_time_2' => null,   // Ditambahkan
                'shift_type' => null,
            ]);
        }

        return redirect()->route('employees.index')->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $details = $employee->employee_shifts()->get()->keyBy('day');
        return view('employees.edit', compact('employee', 'details'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Menambahkan 'bar' pada validasi
            'position' => 'required|in:office,cook,waiter,staff,bar'
        ]);

        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success', 'Employee Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee Deleted');
    }

    // Detail
    public function showDetails(Employee $employee)
    {
        $details = $employee->employee_shifts()->orderBy('day')->get();
        return view('employees.detail', compact('employee', 'details'));
    }

    public function editDetails(Employee $employee)
    {
        // Mengurutkan shift berdasarkan urutan hari yang benar
        $daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $details = $employee->employee_shifts()->get()->sortBy(function ($shift) use ($daysOrder) {
            return array_search($shift->day, $daysOrder);
        })->keyBy('day');

        return view('employees.edit', compact('employee', 'details'));
    }


    // update detail
    public function updateDetails(Request $request, Employee $employee)
    {
        // 1. Validasi data karyawan
        $request->validate([
            'name' => 'required|string|max:255',
            // Menambahkan 'bar' pada validasi
            'position' => 'required|in:office,cook,waiter,staff,bar',
        ]);

        // 2. Update data karyawan
        $employee->update([
            'name' => $request->name,
            'position' => $request->position,
        ]);

        // 3. Update shift mingguan
        $inputDetails = $request->input('detail', []);

        foreach ($inputDetails as $day => $values) {
            // Menggunakan updateOrCreate untuk keamanan jika shift belum ada
            $shift = $employee->employee_shifts()->firstOrNew(['day' => $day]);

            // Siapkan data untuk diupdate
            $updateData = [];

            // PERUBAHAN UTAMA DI SINI: Logika untuk menangani libur dan tipe shift

            // Jika hari ditandai sebagai libur ('is_off')
            if (isset($values['is_off']) && $values['is_off']) {
                $updateData = [
                    'start_time'   => null,
                    'end_time'     => null,
                    'start_time_2' => null, // Pastikan data split juga dikosongkan
                    'end_time_2'   => null,
                    'shift_type'   => null,
                ];
            } else { // Jika hari tidak libur
                $updateData = [
                    'start_time' => $values['start_time'] ?? null,
                    'end_time'   => $values['end_time'] ?? null,
                    'shift_type' => $values['shift_type'] ?? null,
                ];

                // Cek secara spesifik jika shift_type adalah 'split'
                if (isset($values['shift_type']) && $values['shift_type'] === 'split') {
                    $updateData['start_time_2'] = $values['start_time_2'] ?? null;
                    $updateData['end_time_2']   = $values['end_time_2'] ?? null;
                } else {
                    // Jika BUKAN split, pastikan kolom split shift kosong untuk membersihkan data lama
                    $updateData['start_time_2'] = null;
                    $updateData['end_time_2']   = null;
                }
            }

            // Simpan perubahan ke database
            $shift->fill($updateData)->save();
        }

        // 4. Redirect dengan pesan sukses
        return redirect()
            ->back() // Lebih baik kembali ke halaman edit agar bisa melihat perubahan
            ->with('success', 'Data karyawan dan shift mingguan berhasil diperbarui.');
    }
}
