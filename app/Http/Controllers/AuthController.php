<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:USERS,email', 
        'password' => 'required|min:6'
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => \Hash::make($request->password), 
    ]);

    return response()->json(['message' => 'Registrasi Berhasil', 'user' => $user], 201);
    }

    // LOGIN
    public function login(Request $request) {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
        $user = Auth::user();
        
        // Hapus token lama (opsional, agar satu user cuma punya 1 token aktif)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('mobile_token')->plainTextToken;

        // Kirim token ke Flutter
        return response()->json([
            'message' => 'Login Berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

        return response()->json(['message' => 'Email atau Password salah'], 401);
    }

    // LOGOUT
    public function logout(Request $request)
{
    // Menghapus token yang sedang digunakan untuk request ini
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout berhasil, token telah dihapus'
    ], 200);
}
}