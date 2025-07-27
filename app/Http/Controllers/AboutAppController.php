<?php

namespace App\Http\Controllers;

use App\Models\AboutApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // Handle logo dinas
        if ($request->hasFile('logo_dinas') && $request->file('logo_dinas')->isValid()) {
            if ($setting->logo_dinas) {
                Storage::delete('public/' . str_replace('storage/', '', $setting->logo_dinas));
            }
            $data['logo_dinas'] = $request->file('logo_dinas')->store('logos/logo_dinas', 'public');
        }

        // Handle logo puskesmas
        if ($request->hasFile('logo_puskesmas') && $request->file('logo_puskesmas')->isValid()) {
            if ($setting->logo_puskesmas) {
                Storage::delete('public/' . str_replace('storage/', '', $setting->logo_puskesmas));
            }
            $data['logo_puskesmas'] = $request->file('logo_puskesmas')->store('logos/logo_puskesmas', 'public');
        }

        // Handle nama aplikasi
        if ($request->filled('nama_aplikasi')) {
            $data['nama_aplikasi'] = $request->nama_aplikasi;
        }

        // Simpan atau perbarui data
        if (isset($data)) {
            $setting->fill($data);
            $setting->save();
        }

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $setting
        ]);
    }
}