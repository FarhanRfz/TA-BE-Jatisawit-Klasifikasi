<?php

namespace App\Http\Controllers;

use App\Models\StuntingEducation;
use Illuminate\Http\Request;

class StuntingEducationController extends Controller
{
        public function index()
{
    $data = StuntingEducation::first(); // Ambil satu data (karena hanya satu yang digunakan)

    if (!$data) {
        return response()->json([
            'message' => 'Belum ada data edukasi',
            'data' => null
        ], 200);
    }

    return response()->json([
        'message' => 'Data edukasi ditemukan',
        'data' => $data
    ]);
}

    public function storeOrUpdate(Request $request)
{
    $request->validate([
        'judul' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string',
        'informasi_stunting' => 'nullable|string', // tambahkan validasi kolom baru
    ]);

    $data = StuntingEducation::first();

    if ($data) {
        $data->update($request->only(['judul', 'deskripsi', 'informasi_stunting']));
    } else {
        $data = StuntingEducation::create($request->only(['judul', 'deskripsi', 'informasi_stunting']));
    }

    return response()->json([
        'message' => 'Data berhasil disimpan',
        'data' => $data
    ]);
}
}
