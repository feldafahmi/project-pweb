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
            'email' => 'required|email|unique:users,email', 
            'password' => 'required|min:6'
        ]);

        $emailParts = explode('@', $request->email);
        $baseUsername = strtolower($emailParts[0]);
        $username = $baseUsername;
        $counter = 1;
        while (\App\Models\User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter++;
        }

        $nameParts = explode(' ', trim($request->name), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? $nameParts[0];

        $user = \App\Models\User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'first_name' => $firstName,
            'last_name' => $lastName,
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
                'name' => $user->name,
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
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $emailParts = explode('@', $request->email);
        $baseUsername = strtolower($emailParts[0]);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter++;
        }

        $nameParts = explode(' ', trim($request->name), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? $nameParts[0];

        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => Hash::make($request->password),
            'role' => 'user', // Locked to 'user'
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Registrasi Berhasil',
            'name' => $user->name,
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