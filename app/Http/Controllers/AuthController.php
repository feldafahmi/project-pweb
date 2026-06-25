<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // REGISTER — buat akun lalu langsung terbitkan token (auto-login)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    // LOGIN — verifikasi kredensial tanpa membuat session web (stateless token)
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau Password salah'],
            ]);
        }

        // Hapus token lama agar satu user hanya punya satu token aktif
        $user->tokens()->delete();

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // GOOGLE SIGN-IN — verifikasi ID token dari aplikasi, lalu find-or-create user.
    // Login & register menyatu di sini (pola standar social auth).
    public function google(Request $request)
    {
        $data = $request->validate([
            'id_token' => 'required|string',
        ]);

        $clientId = config('services.google.client_id');
        if (empty($clientId)) {
            return response()->json([
                'message' => 'Google Sign-In belum dikonfigurasi di server',
            ], 500);
        }

        // Verifikasi tanda tangan & audience ID token langsung ke Google.
        $client = new \Google\Client(['client_id' => $clientId]);
        try {
            $payload = $client->verifyIdToken($data['id_token']);
        } catch (\Throwable $e) {
            $payload = false;
        }

        if (! $payload) {
            throw ValidationException::withMessages([
                'id_token' => ['Token Google tidak valid atau kedaluwarsa'],
            ]);
        }

        $googleId = $payload['sub'];
        $email    = $payload['email'] ?? null;
        $name     = $payload['name'] ?? ($email ? explode('@', $email)[0] : 'User Mark-Up');
        $avatar   = $payload['picture'] ?? null;
        $verified = ! empty($payload['email_verified']);

        if (! $email) {
            throw ValidationException::withMessages([
                'id_token' => ['Akun Google tidak memiliki email'],
            ]);
        }

        // 1) cocokkan via google_id; 2) tautkan akun email lama; 3) buat baru.
        $user = User::where('google_id', $googleId)->first();

        if (! $user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                // Tautkan akun email/password lama ke identitas Google.
                $user->google_id = $googleId;
                $user->avatar ??= $avatar;
                $user->save();
            } else {
                $user = User::create([
                    'name'              => $name,
                    'email'             => $email,
                    'google_id'         => $googleId,
                    'avatar'            => $avatar,
                    'password'          => null,
                    'email_verified_at' => $verified ? now() : null,
                ]);
            }
        }

        // Satu user = satu token aktif (konsisten dengan login biasa).
        $user->tokens()->delete();
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login Google Berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    // LOGOUT — hapus token yang sedang dipakai untuk request ini
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil, token telah dihapus',
        ], 200);
    }
}
