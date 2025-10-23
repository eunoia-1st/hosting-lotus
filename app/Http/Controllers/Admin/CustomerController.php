<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = \App\Models\Customer::whereHas('feedbacks', function ($q) use ($request) {
            if ($request->filled('start_date')) {
                $q->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $q->whereDate('created_at', '<=', $request->end_date);
            }
        })
            ->with(['feedbacks' => function ($q) use ($request) {
                if ($request->filled('start_date')) {
                    $q->whereDate('created_at', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $q->whereDate('created_at', '<=', $request->end_date);
                }
                $q->with(['answers.question.question_category']);
            }])
            ->get();

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Menampilkan Feedback yang telah diisi sesuai Customer
     */
    // public function showFeedbacks(string $id)
    // {
    //     $customer = Customer::findOrFail($id);

    //     $feedbacks = Feedback::with([
    //         'answers.question.question_category'
    //     ])
    //         ->where('customer_id', $customer->id)
    //         ->latest()
    //         ->get();

    //     return view('customers.feedbacks', compact('customer', 'feedbacks'));
    // }

    public function feedbackDetail($id)
    {
        $feedbackDetail = Feedback::with([
            'answers.question.question_category',
            'answers.question.question_options',
            'customer',
            'seat',
            'employees.employee_shifts'
        ])->findOrFail($id);

        $customerName = $feedbackDetail->customer->name ?? 'Anonim';

        return view('customers.feedbackDetail', compact('feedbackDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string|max:255',
        ]);

        // Update data customer
        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // sebelum hapus customer, putuskan relasi feedbacknya
        \App\Models\Feedback::where('customer_id', $customer->id)
            ->update(['customer_id' => null]);

        // baru hapus customer
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer Deleted, feedback tetap dipertahankan.');
    }

    public function export(Request $request)
    {
        $customers = \App\Models\Customer::whereHas('feedbacks', function ($q) use ($request) {
            if ($request->filled('start_date')) {
                $q->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $q->whereDate('created_at', '<=', $request->end_date);
            }
        })->with(['feedbacks' => function ($q) use ($request) {
            if ($request->filled('start_date')) {
                $q->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $q->whereDate('created_at', '<=', $request->end_date);
            }
        }])->get();

        $data = [];
        foreach ($customers as $cust) {
            if ($cust->feedbacks->count() > 0) {
                $data[] = [
                    'Nama' => $cust->name,
                    'Email' => $cust->email,
                    'No HP' => $cust->phone,
                    'Alamat' => $cust->address,
                ];
            }
        }

        $filename = 'customers_export_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new class($data) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            WithStyles,
            WithColumnWidths
        {
            private $data;
            public function __construct($data)
            {
                $this->data = $data;
            }
            public function collection()
            {
                return collect($this->data);
            }
            public function headings(): array
            {
                return ['Nama', 'Email', 'No HP', 'Alamat'];
            }
            public function styles(Worksheet $sheet)
            {
                $lastRow = count($this->data) + 1;
                // Header style
                $sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFF00']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
                // Isi tabel rata kiri dan border
                $sheet->getStyle("A2:D$lastRow")->applyFromArray([
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
            }
            public function columnWidths(): array
            {
                return [
                    'A' => 20,
                    'B' => 30,
                    'C' => 18,
                    'D' => 60,
                ];
            }
        }, $filename);
    }
}
