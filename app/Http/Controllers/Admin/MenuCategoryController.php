<?php

namespace App\Http\Controllers\Admin;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MenuCategory::all();
        return view('menu_categories.index', compact('categories'));
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
        $request->validate([
            'name' => 'required|unique:menu_categories,name'
        ]);
        MenuCategory::create(['name' => $request->name]);
        return redirect()->route('menu-categories.index')->with('success', 'Category added');
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
    public function edit(MenuCategory $menuCategory)
    {
        return view('menu_categories.edit', compact('menuCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuCategory $menuCategory)
    {
        $request->validate([
            'name' => 'required|unique:menu_categories,name,' . $menuCategory->id,
        ]);
        $menuCategory->update(['name' => $request->name]);

        return redirect()->route('menu-categories.index')->with('success', 'Category updated');
    }

    public function destroy(MenuCategory $menuCategory)
    {
        // $menuCategory->delete();
        // return back()->ith('success', 'Category Deleted');
        try {
            $menuCategory->delete();
            return back()->with('success', 'Category deleted. All related menu items were uncategorized.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
