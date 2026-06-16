<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', 
            'institution' => 'nullable|string|max:255',
            'password' => 'required|min:6'
        ]);

        $user = \App\Models\User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'institution' => $request->institution,
            'password' => \Hash::make($request->password), 
            'role' => 'user',
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

    // WEB LOGIN
    public function webLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return response()->json([
                'message' => 'Login Berhasil',
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
            ], 200);
        }

        return response()->json([
            'message' => 'Email atau Password salah.'
        ], 422);
    }

    // WEB REGISTER
    public function webRegister(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'institution' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'institution' => $request->institution,
            'password' => Hash::make($request->password),
            'role' => 'user', // Locked to 'user'
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Registrasi Berhasil',
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'role' => $user->role,
        ], 201);
    }

    // WEB LOGOUT
    public function webLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logout Berhasil'], 200);
        }

        return redirect('/')->with('success', 'Berhasil Keluar.');
    }
}