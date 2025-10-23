<?php

namespace App\Http\Controllers\Customer;

use App\Models\Seat;
use App\Models\Answer;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Feedback;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\EmployeeFeedback;
use App\Models\QuestionCategory;
use App\Http\Controllers\Controller;

class FeedbackFormController extends Controller
{
    // menampilkan pilihan kategori
    public function showCategories()
    {
        $categories = QuestionCategory::where('status', 'active')->get();

        return view('feedback_forms.categories', compact('categories'));
    }

    // menampilkan pertanyaan setelah user memilih kategori
    public function showQuestions($categoryId)
    {
        $category = QuestionCategory::findOrFail($categoryId);

        $questions = Question::with('question_options')->where('question_category_id', $categoryId)->get();

        $seats = Seat::all();

        if ($questions->isEmpty()) {
            dd('Tidak ada pertanyaan untuk kategori ini', $categoryId);
        }

        return view('feedback_forms.questions', compact('category', 'questions', 'seats'));
    }

    public function submit(Request $request, $categoryId)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id',
            'answers' => 'required|array',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255'
        ]);

        if (empty($request->email) && empty($request->phone)) {
            return back()->withErrors([
                'email' => 'Email atau nomor telepon wajib diisi.',
                'phone' => 'Email atau nomor telepon wajib diisi.',
            ])->withInput();
        }

        $customer = Customer::where(function ($query) use ($request) {
            if (!empty($request->email)) {
                $query->orWhere('email', $request->email);
            }

            if (!empty($request->phone)) {
                $query->orWhere('phone', $request->phone);
            }
        })->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address ?? null,
            ]);
        }

        // Buat feedback baru
        $feedback = Feedback::create([
            'seat_id' => $request->seat_id,
            'customer_id' => $customer->id, // Atau isi jika login
        ]);

        // Simpan jawaban
        foreach ($request->answers as $questionId => $answer) {
            $question = Question::with('question_options')->find($questionId); // ambil tipe pertanyaannya
            if ($question->question_type === 'checkbox') {
                foreach ($answer as $optionId) {
                    Answer::create([
                        'feedback_id'  => $feedback->id,
                        'question_id'  => $questionId,
                        'option_id'    => $optionId,
                        // Tidak perlu answer_text untuk checkbox
                    ]);
                }
            } elseif ($question->question_type === 'option') {
                Answer::create([
                    'feedback_id'  => $feedback->id,
                    'question_id'  => $questionId,
                    'option_id'    => $answer,
                    // Tidak perlu answer_text untuk option
                ]);
            } else {
                // Text input
                if (is_array($answer)) {
                    $answer = implode(', ', $answer);
                }
                Answer::create([
                    'feedback_id'  => $feedback->id,
                    'question_id'  => $questionId,
                    'answer_text'  => $answer,
                ]);
            }
        }

        $now = now();
        // WAJIB menggunakan strtolower() agar 'Friday' menjadi 'friday'
        $today = strtolower($now->format('l'));
        $time = $now->format('H:i:s');

        // Ambil hanya ID karyawan yang jadwalnya cocok
        $workingEmployeeIds = Employee::whereHas('employee_shifts', function ($query) use ($today, $time) {
            $query->where('day', $today)
                ->where(function ($q) use ($time) {
                    // Kondisi 1: Waktu cocok dengan shift utama
                    $q->where(function ($subq) use ($time) {
                        $subq->whereNotNull('start_time')
                            ->whereNotNull('end_time')
                            ->where('start_time', '<=', $time)
                            ->where('end_time', '>=', $time);
                    })
                        // ATAU Kondisi 2: Waktu cocok dengan shift split
                        ->orWhere(function ($subq) use ($time) {
                            $subq->whereNotNull('start_time_2')
                                ->whereNotNull('end_time_2')
                                ->where('start_time_2', '<=', $time)
                                ->where('end_time_2', '>=', $time);
                        });
                });
        })->pluck('id');


        // --- CARA MENYIMPAN RELASI YANG LEBIH BAIK ---
        // Gunakan attach() untuk melampirkan semua karyawan yang bekerja dalam satu query
        if ($workingEmployeeIds->isNotEmpty()) {
            $feedback->employees()->attach($workingEmployeeIds);
        }

        return redirect()->route('feedback.success');
    }
}
