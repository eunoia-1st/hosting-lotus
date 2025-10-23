<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
// Pastikan model Admin di-import
use App\Models\Admin;

class PengaturanController extends Controller
{
    public function edit()
    {
        // Untuk view, ini tidak masalah
        $admin = Auth::guard('admin')->user();
        return view('admin.pengaturan.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        // 1. Dapatkan ID admin yang sedang login
        $adminId = Auth::guard('admin')->id();

        // Validasi dulu
        $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins')->ignore($adminId), // Gunakan $adminId untuk validasi
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // 2. Ambil Eloquent Model yang LENGKAP dari database menggunakan ID
        $admin = Admin::findOrFail($adminId);

        // 3. Lakukan update pada model tersebut
        $admin->username = $request->username;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        // 4. Panggil method save() pada Eloquent model. Ini PASTI berhasil.
        $admin->save();

        return redirect()->route('admin.pengaturan.edit')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
