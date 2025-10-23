<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\QuestionCategory;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category_id)
    {
        $category = QuestionCategory::with('questions')->findOrFail($category_id);

        return view('questions.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($category_id)
    {
        $category = QuestionCategory::findOrFail($category_id);

        return view('questions.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $category_id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:checkbox,option,text',
        ]);

        Question::create([
            'question_category_id' => $category_id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
        ]);

        // redirect ke halaman edit kategori, bukan ke index pertanyaan
        return redirect()
            ->route('question-categories.edit', $category_id)
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
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
    public function edit(string $id)
    {
        $question = Question::findOrFail($id);

        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'question_text' => 'required|string|max:255',
            'question_type' => 'required|in:checkbox,option,text',
        ]);

        // Ambil pertanyaan
        $question = Question::findOrFail($id);

        // Update data
        $question->update([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
        ]);

        // Redirect balik ke halaman edit kategori (supaya langsung lihat list pertanyaan)
        return redirect()
            ->route('question-categories.edit', $question->question_category_id)
            ->with('success', 'Pertanyaan berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // cari pertanyaan
        $question = Question::findOrFail($id);

        // simpan id kategori dulu supaya bisa redirect balik
        $category_id = $question->question_category_id;

        // hapus pertanyaan
        $question->delete();

        // redirect ke halaman edit kategori
        return redirect()
            ->route('question-categories.edit', $category_id)
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
