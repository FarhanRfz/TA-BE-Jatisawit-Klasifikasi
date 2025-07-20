<?php

namespace App\Http\Controllers;

use App\Models\ClassificationHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;


class StuntingPredictController extends Controller
{
    public function predict(Request $request)
    {
        // Validasi input dari frontend
        $request->validate([
            'nama_anak' => 'required|string|max:100',
            'umur' => 'required|numeric|min:6|max:24',
            'berat_badan' => 'required|numeric|min:4|max:16',
            'tinggi_badan' => 'required|numeric|min:60|max:100',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        // Panggil endpoint FastAPI untuk prediksi
        $response = Http::post('http://127.0.0.1:8001/predict', [
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'tinggi' => $request->tinggi_badan,
            'berat' => $request->berat_badan,
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Gagal menghubungi model ML.'], 500);
        }

        $prediction = $response->json();
        $status = $prediction['status'];

        // Mapping status untuk konsistensi dengan enum di tabel
        $stuntingStatus = match (strtolower($prediction['status'])) {
            'stunted' => 'stunting',
            'normal' => 'normal',
            default => 'stunting', // Fallback jika ada kesalahan
        };

        // Deskripsi status berdasarkan hasil
        $deskripsiStatus = match ($stuntingStatus) {
    'stunting' => Arr::random([
        'Anak berisiko stunting. Konsultasikan ke puskesmas.',
        'Perhatikan pola makan dan pertumbuhan anak secara berkala.',
    ]),
    'normal' => Arr::random([
        'Status normal berdasarkan data yang diberikan.',
        'Pertumbuhan anak sesuai standar WHO, lanjutkan pemantauan.',
    ]),
    default => 'Tidak dapat menentukan status stunting anak.',
};

        // Simpan ke classification_history
        $classification = ClassificationHistory::create([
            'id_users' => Auth::id(),
            'nama_anak' => $request->nama_anak,
            'umur' => $request->umur,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'status_stunting' => $stuntingStatus,
            'deskripsi_status' => $deskripsiStatus,
            'waktu_klasifikasi' => now(),
            'exported' => false,
        ]);

        return response()->json([
            'status' => $stuntingStatus,
            'deskripsi_status' => $deskripsiStatus,
            'classification_id' => $classification->id_ch,
        ]);
    }

    public function export($id)
    {
        $classification = ClassificationHistory::where('id_ch', $id)
            ->where('id_users', Auth::id())
            ->first();

        if (!$classification) {
            return response()->json(['error' => 'Data tidak ditemukan atau Anda tidak berhak mengakses data ini.'], 404);
        }

        $pdf = Pdf::loadView('pdf.klasifikasi', ['data' => $classification]);

    return $pdf->download('hasil_klasifikasi_anak_' . $classification->nama_anak . '.pdf');
}

    public function history()
    {
        $user = Auth::user();
        $history = $user->classificationHistories()->latest()->get();

        return response()->json($history);
    }
}
