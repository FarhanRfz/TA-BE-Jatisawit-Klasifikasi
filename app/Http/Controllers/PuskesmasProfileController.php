<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PuskesmasProfile;
use Illuminate\Support\Facades\Storage;

class PuskesmasProfileController extends Controller
{
    public function index()
    {
        $data = PuskesmasProfile::first();

        if (!$data) {
            return response()->json([
                'message' => 'Data profil belum tersedia',
                'data' => null
            ]);
        }

        return response()->json($data);
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'foto_bersama' => 'nullable|image|mimes:jpeg,jpg,png|max:3072',
            'struktur_organisasi' => 'nullable|image|mimes:jpeg,jpg,png|max:3072',
            'peta_wilayah_kerja' => 'nullable|image|mimes:jpeg,jpg,png|max:3072',
            'judul' => 'nullable|string|max:100',
            'deskripsi_profil' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'motto_tatanilai' => 'nullable|string|max:3072',
        ]);

        $profil = PuskesmasProfile::first();

        $data = [
            'judul' => $request->judul,
            'deskripsi_profil' => $request->deskripsi_profil,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'tujuan' => $request->tujuan,
            'motto_tatanilai' => $request->motto_tatanilai,
        ];

        // Upload gambar jika ada
        if ($request->hasFile('foto_bersama')) {
            if ($profil && $profil->foto_bersama) {
                Storage::delete('public/' . $profil->foto_bersama);
            }
            $data['foto_bersama'] = $request->file('foto_bersama')->store('puskesmas/foto_bersama', 'public');
        }

        if ($request->hasFile('struktur_organisasi')) {
            if ($profil && $profil->struktur_organisasi) {
                Storage::delete('public/' . $profil->struktur_organisasi);
            }
            $data['struktur_organisasi'] = $request->file('struktur_organisasi')->store('puskesmas/struktur_organisasi', 'public');
        }

        if ($request->hasFile('peta_wilayah_kerja')) {
            if ($profil && $profil->peta_wilayah_kerja) {
                Storage::delete('public/' . $profil->peta_wilayah_kerja);
            }
            $data['peta_wilayah_kerja'] = $request->file('peta_wilayah_kerja')->store('puskesmas/peta_wilayah_kerja', 'public');
        }

        if ($profil) {
            $profil->update($data);
        } else {
            $profil = PuskesmasProfile::create($data);
        }

        return response()->json([
            'message' => 'Data profil berhasil disimpan',
            'data' => $profil
        ]);
    }
}
