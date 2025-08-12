<?php

namespace App\Http\Controllers;

use App\Models\OrtuAnak;
use Illuminate\Http\Request;

class OrtuAnakController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya pengguna yang terautentikasi yang dapat mengakses controller ini
    }

    public function index()
    {
        $data = OrtuAnak::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ibu' => 'required|string|max:255',
            'nama_bapak' => 'required|string|max:255',
            'nama_anak' => 'required|string|max:255',
            'jenis_kelamin_anak' => 'required|in:L,P',
            'gol_darah_anak' => 'nullable|string',
            'tanggal_lahir_anak' => 'required|date',
            'pekerjaan_ibu' => 'required|string|max:255',
            'alamat_rumah' => 'required|string',
            'status' => 'in:aktif,nonaktif', // Validasi status jika diberikan
        ]);

        $data = $request->all();
        $ortuAnak = OrtuAnak::create($data);

        return response()->json(['message' => 'Data berhasil ditambahkan', 'data' => $ortuAnak], 201);
    }

    public function show($id_ota)
    {
        $data = OrtuAnak::findOrFail($id_ota);
        return response()->json($data);
    }

    public function update(Request $request, $id_ota)
    {
        $ortuAnak = OrtuAnak::findOrFail($id_ota);

        $request->validate([
            'nama_ibu' => 'sometimes|string|max:255',
            'nama_bapak' => 'sometimes|string|max:255',
            'nama_anak' => 'sometimes|string|max:255',
            'jenis_kelamin_anak' => 'sometimes|in:L,P',
            'gol_darah_anak' => 'nullable|string',
            'tanggal_lahir_anak' => 'sometimes|date',
            'pekerjaan_ibu' => 'sometimes|string|max:255',
            'alamat_rumah' => 'sometimes|string',
            'status' => 'sometimes|in:aktif,nonaktif', // Validasi status jika diberikan
        ]);

        $ortuAnak->update($request->all());

        return response()->json(['message' => 'Data berhasil diperbarui', 'data' => $ortuAnak]);
    }

    public function destroy($id_ota)
    {
        $ortuAnak = OrtuAnak::findOrFail($id_ota);
        $ortuAnak->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function getTotalBalita()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $total = OrtuAnak::count();
        return response()->json(['total' => $total]);
    }
}
