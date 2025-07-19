<?php

namespace App\Http\Controllers;

use App\Models\AboutApp;
use Illuminate\Http\Request;

class AboutAppController extends Controller
{

    public function index()
{
    $setting = AboutApp::first();

    if (!$setting) {
        return response()->json([
            'message' => 'Belum ada data pengaturan aplikasi',
            'data' => null
        ], 200);
    }

    return response()->json([
        'message' => 'Data pengaturan aplikasi ditemukan',
        'data' => $setting
    ]);
}


public function storeOrUpdate(Request $request)
{
    $setting = AboutApp::first() ?? new AboutApp();

    $request->validate([
        'logo_dinas' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        'logo_puskesmas' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        'nama_aplikasi' => 'nullable|string|max:255',
    ]);

    if ($request->hasFile('logo_dinas') && $request->file('logo_dinas')->isValid()) {
    $file = $request->file('logo_dinas');
    $path = $file->store('logos', 'public'); // disimpan di storage/app/public/logos
    $setting->logo_dinas = 'storage/' . $path; // agar bisa diakses via URL
}

if ($request->hasFile('logo_puskesmas') && $request->file('logo_puskesmas')->isValid()) {
    $file = $request->file('logo_puskesmas');
    $path = $file->store('logos', 'public');
    $setting->logo_puskesmas = 'storage/' . $path;
}


    if ($request->filled('nama_aplikasi')) {
        $setting->nama_aplikasi = $request->nama_aplikasi;
    }

    $setting->save();

    return response()->json([
        'message' => 'Data berhasil disimpan',
        'data' => $setting
    ]);
}



}
