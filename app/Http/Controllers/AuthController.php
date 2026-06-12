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

    // LOGOUT — hapus token yang sedang dipakai untuk request ini
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil, token telah dihapus',
        ], 200);
    }
}
