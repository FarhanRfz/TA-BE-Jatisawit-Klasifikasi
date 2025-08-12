<?php


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AboutAppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PuskesmasContactController;
use App\Http\Controllers\PuskesmasProfileController;
use App\Http\Controllers\StuntingEducationController;
use App\Http\Controllers\StuntingPredictController;
use App\Http\Controllers\OrtuAnakController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route Kontak
Route::get('/puskesmas-kontak', [PuskesmasContactController::class, 'index']); // GET semua data kontak
Route::get('/puskesmas-kontak/{id_contacts}', [PuskesmasContactController::class, 'show']);// GET satu data berdasarkan ID
Route::post('/puskesmas-kontak', [PuskesmasContactController::class, 'store']); // POST simpan kontak baru
Route::put('/puskesmas-kontak/{id_contacts}', [PuskesmasContactController::class, 'update']); // PUT/PATCH update kontak
Route::delete('/puskesmas-kontak/{id_contacts}', [PuskesmasContactController::class, 'destroy']); // DELETE hapus kontak

//Route Edukasi
Route::get('/edukasi-stunting', [StuntingEducationController::class, 'index']);
Route::post('/edukasi-stunting', [StuntingEducationController::class, 'storeOrUpdate']);

//Route Profil
Route::get('/profil-puskesmas', [PuskesmasProfileController::class, 'index']);
Route::post('/profil-puskesmas', [PuskesmasProfileController::class, 'storeOrUpdate']);

//Route AboutApp
Route::post('/about-app', [AboutAppController::class, 'storeOrUpdate']);
Route::get('/about-app', [AboutAppController::class, 'index']);



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
    ? response()->json(['message' => 'Link reset telah dikirim ke email.'])
    : response()->json(['message' => __($status)], 400);

});
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Password berhasil direset.'])
        : response()->json(['message' => 'Gagal mereset password.'], 400);
});


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/user/update', [AuthController::class, 'updateUser']);
    Route::post('/predict', [StuntingPredictController::class, 'predict']);
    Route::get('/export/{id}', [StuntingPredictController::class, 'export']);
    Route::get('/history', [StuntingPredictController::class, 'history']);
    Route::get('/export/check/{id}', [StuntingPredictController::class, 'checkExport']);
    Route::get('/refresh-token', [AuthController::class, 'refreshToken']);
    
    Route::prefix('admin')->group(function () {
        Route::get('/ortu-anak', [OrtuAnakController::class, 'index']);
        Route::post('/ortu-anak', [OrtuAnakController::class, 'store']);
        Route::get('/ortu-anak/{id_ota}', [OrtuAnakController::class, 'show']);
        Route::put('/ortu-anak/{id_ota}', [OrtuAnakController::class, 'update']);
        Route::delete('/ortu-anak/{id_ota}', [OrtuAnakController::class, 'destroy']);
        Route::get('/orangtua-anak/total', [OrtuAnakController::class, 'getTotalBalita']);

        Route::get('/history', [StuntingPredictController::class, 'adminHistory']);
        Route::get('/riwayat-klasifikasi/total', [StuntingPredictController::class, 'getTotalRiwayat']);
    });
});


