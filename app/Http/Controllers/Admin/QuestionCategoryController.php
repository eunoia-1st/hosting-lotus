<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\QuestionCategory;
use App\Jobs\ActivateCategoryJob;
use App\Http\Controllers\Controller;

class QuestionCategoryController extends Controller
{
    public function index()
    {
        $categories = QuestionCategory::all();
        return view('question_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('question_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:question_categories,name',
        ]);

        QuestionCategory::create([
            'name' => $request->name,
            'status' => 'inactive', // default inactive
        ]);

        return redirect()->route('question-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(QuestionCategory $question_category)
    {
        return view('question_categories.edit', compact('question_category'));
    }

    public function update(Request $request, QuestionCategory $question_category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:question_categories,name,' . $question_category->id,
        ]);

        $question_category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('question-categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(QuestionCategory $question_category)
    {
        $question_category->delete();
        return redirect()->route('question-categories.index')->with('success', 'Category Deleted');
    }


    /**
     * Toggle status sesuai tombol
     */
    public function toggle(QuestionCategory $category)
    {
        if ($category->status === 'inactive') {
            // Inactive → Pending
            $category->update(['status' => 'pending']);

            // Dispatch job sesuai jam global
            ActivateCategoryJob::dispatch($category->id);
        } elseif ($category->status === 'pending') {
            // Pending → Inactive
            $category->update(['status' => 'inactive']);
        } elseif ($category->status === 'active') {
            // Active → Inactive, jika bukan jam buka
            if ($category->isActiveHours()) {
                return back()->with('error', 'Tidak bisa menonaktifkan saat jam buka!');
            }
            $category->update(['status' => 'inactive']);
        }

        return back()->with('success', 'Status kategori diperbarui.');
    }
}
