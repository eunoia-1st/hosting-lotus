<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\QuestionOption;
use App\Http\Controllers\Controller;

class QuestionOptionController extends Controller
{
    public function index(Question $question)
    {
        $options = $question->question_options;
        return view('question_options.index', compact('question', 'options'));
    }

    public function create(Question $question)
    {
        return view('question_options.create', compact('question'));
    }

    public function store(Request $request, Question $question)
    {
        $request->validate([
            'question_value' => 'required|string',
        ]);

        $question->question_options()->create([
            'question_value' => $request->question_value,
        ]);

        return redirect()->route('question-options.index', $question->id)
            ->with('success', 'Opsi pertanyaan berhasil ditambahkan.');
    }


    public function edit(Question $question, QuestionOption $option)
    {
        $option->load('question');

        if (!$option->question) {
            abort(404, 'Relasi pertanyaan tidak ditemukan.');
        }

        return view('question_options.edit', [
            'questionOption' => $option,
            'question' => $option->question,
        ]);
    }



    public function update(Request $request, Question $question, QuestionOption $option)
    {
        $request->validate([
            'question_value' => 'required|string',
        ]);

        $option->update([
            'question_value' => $request->question_value,
        ]);

        return redirect()->route('question-options.index', ['question' => $question->id])
            ->with('success', 'Opsi pertanyaan berhasil diperbarui.');
    }


    public function destroy($question_id, $option_id)
    {
        $option = QuestionOption::where('question_id', $question_id)
            ->where('id', $option_id)
            ->firstOrFail();

        $option->delete();

        return redirect()
            ->route('question-options.index', $question_id)
            ->with('success', 'Opsi berhasil dihapus!');
    }
}
