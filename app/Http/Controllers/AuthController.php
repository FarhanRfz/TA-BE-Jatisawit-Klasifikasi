<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('login');
    }

    // REGISTER
    public function register(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'nama_lengkap_orangtua' => 'required|string',
            ], [
                'username.unique' => 'Username sudah digunakan.',
                'email.unique' => 'Email sudah digunakan.',
            ]);

            // Generate OTP
            $otp = rand(100000, 999999);

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nama_lengkap_orangtua' => $request->nama_lengkap_orangtua,
                'role' => 'user',
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
                'is_verified' => false,
            ]);

            // Kirim email OTP
            Mail::to($user->email)->send(
                new OtpVerificationMail($otp)
            );

            return response()->json(['message' => 'Registrasi berhasil! Cek email Anda untuk OTP.'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json(['message' => 'OTP tidak valid atau sudah kedaluwarsa'], 400);
        }

        $user->is_verified = true;
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Auto login
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Verifikasi berhasil',
            'token' => $token,
            'role' => $user->role,
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            info('Login failed: User not found for username ' . $request->username);
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            info('Login failed: Password mismatch for username ' . $request->username);
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        if (!$user->is_verified) {
            info('Login failed: Account not verified for username ' . $request->username);
            return response()->json(['message' => 'Akun belum diverifikasi. Silakan cek email untuk OTP.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $expiry = config('sanctum.expiration', 60); // Default 60 menit jika tidak diatur

        info('Login successful for username ' . $request->username . ' with role ' . $user->role);

        return response()->json([
            'token' => $token,
            'role' => $user->role,
            'user' => $user,
            'token_expires_in_minutes' => $expiry,
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    // FORGOT PASSWORD
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Link reset dikirim ke email Anda.'])
            : response()->json(['message' => __($status)], 400);
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password berhasil direset.'])
            : response()->json(['message' => __($status)], 400);
    }

    public function getUser(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak terautentikasi.'], 401);
        }

        return response()->json([
            'data' => [
                'username' => $user->username,
                'email' => $user->email,
                'nama_lengkap_orangtua' => $user->nama_lengkap_orangtua,
            ],
        ]);
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Pengguna tidak terautentikasi.'], 401);
        }

        $request->validate([
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user->id_users, 'id_users')],
            'nama_lengkap_orangtua' => 'sometimes|string|max:255',
            'password' => 'nullable|confirmed|min:8',
            'password_confirmation' => 'nullable|same:password',
        ]);

        $data = $request->only(['nama_lengkap_orangtua']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Tambahkan pengecekan apakah update berhasil
        $updated = $user->update($data);
        if (!$updated) {
            return response()->json(['error' => 'Gagal memperbarui profil.'], 500);
        }

        return response()->json(['message' => 'Profil berhasil diperbarui!']);
    }

    public function refreshToken(Request $request)
{
    $token = $request->bearerToken();
    if (!$token) {
        return response()->json(['message' => 'Token tidak ditemukan'], 401);
    }

    $accessToken = PersonalAccessToken::findToken($token);
    if (!$accessToken || !$accessToken->tokenable) {
        return response()->json(['message' => 'Token tidak valid'], 401);
    }

    $user = $accessToken->tokenable;
    Auth::login($user);

    $newToken = $user->createToken('auth_token')->plainTextToken;
    return response()->json(['token' => $newToken], 200)->header('Authorization', 'Bearer ' . explode('|', $newToken)[1]);
}
}
