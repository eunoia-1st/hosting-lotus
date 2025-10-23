<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with('menu_category')->get();
        $categories = MenuCategory::all();
        return view('menus.index', compact('menus', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MenuCategory::all();
        return view('menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|unique:menus,name',
            'menu_category_id' => 'nullable|exists:menu_categories,id',
            'price' => 'required|numeric',
        ]);
        Menu::create($request->only([
            'name',
            'menu_category_id',
            'price'
        ]));
        // jika validasi berhasil maka kembali ke view menus.index
        return redirect()->route('menus.index')->with('success', 'Menu Added');
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
    public function edit(Menu $menu, MenuCategory $categories)
    {
        $categories = MenuCategory::all();
        return view('menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|unique:menus,name,' . $menu->id,
            'menu_category_id' => 'nullable|exists:menu_categories,id',
            'price' => 'required|numeric'
        ]);
        $menu->update($request->only([
            'name',
            'menu_category_id',
            'price'
        ]));

        return redirect()->route('menus.index')->with('success', 'Menu updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu Deleted');
    }
}
