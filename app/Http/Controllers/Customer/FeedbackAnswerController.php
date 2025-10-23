<?php

namespace App\Http\Controllers\Customer;

use App\Models\Feedback;
use App\Models\QuestionCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeedbackAnswerController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with([
            'answers.question.question_category'
        ])->latest();

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereHas('answers.question.question_category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        $feedbackAnswer = $query->get();
        $categories = QuestionCategory::orderBy('name')->get();

        return view('feedback_answers.index', compact('feedbackAnswer', 'categories'));
    }

    public function show($id)
    {
        $feedbackAnswer = Feedback::with([
            'answers.question.question_category',
            'answers.question.question_options',
            'customer',
            'seat',
            'employees.employee_shifts'
        ])->findOrFail($id);

        return view('feedback_answers.show', compact('feedbackAnswer'));
    }

    public function export(Request $request)
    {
        $query = Feedback::with([
            'answers.question.question_category',
            'answers.question.question_options',
            'customer',
            'seat',
            'employees.employee_shifts'
        ])->latest();

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereHas('answers.question.question_category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        $feedbacks = $query->get();
        $data = [];
        $no = 1;

        foreach ($feedbacks as $feedback) {
            $categories = $feedback->answers
                ->pluck('question.question_category.name')
                ->unique()
                ->implode(', ');

            $customerName  = $feedback->customer->name ?? 'Anonim';
            $customerPhone = $feedback->customer->phone ?? '-';
            $customerEmail = $feedback->customer->email ?? '-';
            $seat          = $feedback->seat->name ?? '-';

            // Bersihkan karyawan (tanpa ,,,,)
            $employees = $feedback->employees
                ->groupBy('position_label')
                ->map(function ($group, $position) {
                    $names = $group->map(function ($emp) {
                        $shift = $emp->employee_shifts->pluck('shift_name')->filter()->implode(', ');
                        return $emp->name . ($shift ? " ($shift)" : "");
                    })->filter()->implode(', ');
                    return $position . ': ' . $names;
                })
                ->filter()
                ->implode("\n");

            // Gabungkan pertanyaan & jawaban
            $pertanyaanList = [];
            $jawabanList = [];
            foreach ($feedback->answers->groupBy('question_id') as $answersGroup) {
                $question = $answersGroup->first()->question->question_text ?? '-';
                $type     = $answersGroup->first()->question->question_type ?? '';
                $jawaban = '-';

                if ($type === 'text') {
                    $jawaban = $answersGroup->first()->answer_text;
                } elseif ($type === 'option') {
                    $jawaban = $answersGroup->first()
                        ->question
                        ->question_options
                        ->where('id', $answersGroup->first()->option_id)
                        ->pluck('question_value')
                        ->first() ?? '-';
                } elseif ($type === 'checkbox') {
                    $allSelected = $answersGroup->flatMap(function ($ans) {
                        $ids = $ans->option_id;
                        if (is_string($ids) && str_starts_with($ids, '[')) {
                            return json_decode($ids, true) ?: [];
                        }
                        if (is_string($ids)) {
                            return array_map('trim', explode(',', $ids));
                        }
                        return [$ids];
                    })->filter()->unique();

                    $jawaban = $answersGroup->first()
                        ->question
                        ->question_options
                        ->whereIn('id', $allSelected)
                        ->pluck('question_value')
                        ->implode(', ');
                }

                $pertanyaanList[] = $question;
                $jawabanList[] = $jawaban;
            }

            $data[] = [
                'No'             => $no,
                'Kategori'       => $categories,
                'Tanggal Submit' => $feedback->created_at->format('d-m-Y H:i'),
                'Nama Customer'  => $customerName,
                'No HP'          => $customerPhone,
                'Email'          => $customerEmail,
                'Seat'           => $seat,
                'Karyawan'       => $employees,
                'Pertanyaan'     => implode("\n", $pertanyaanList),
                'Jawaban'        => implode("\n", $jawabanList),
            ];

            $no++;
        }

        $filename = 'feedback_export_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new class($data) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithColumnWidths
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
                return [
                    'No',
                    'Kategori',
                    'Tanggal Submit',
                    'Nama Customer',
                    'No HP',
                    'Email',
                    'Seat',
                    'Karyawan',
                    'Pertanyaan',
                    'Jawaban'
                ];
            }
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                $lastRow = count($this->data) + 1;
                // Header style: bold, center, kuning, border
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFEB3B']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
                // Isi tabel rata kiri dan border
                $sheet->getStyle("A2:J$lastRow")->applyFromArray([
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
                // Wrap text untuk kolom Karyawan, Pertanyaan & Jawaban
                $sheet->getStyle("H2:H$lastRow")->getAlignment()->setWrapText(true);
                $sheet->getStyle("I2:J$lastRow")->getAlignment()->setWrapText(true);
                // Freeze row pertama
                $sheet->freezePane('A2');
            }
            public function columnWidths(): array
            {
                return [
                    'A' => 5,
                    'B' => 18,
                    'C' => 18,
                    'D' => 20,
                    'E' => 15,
                    'F' => 25,
                    'G' => 12,
                    'H' => 30,
                    'I' => 65,
                    'J' => 65,
                ];
            }
        }, $filename);
    }
}
