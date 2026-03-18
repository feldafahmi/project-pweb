<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Di function register
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


    public function login(Request $request) {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'message' => 'Login Berhasil',
                'user' => $user
            ], 200);
        }

        return response()->json(['message' => 'Email atau Password salah'], 401);
    }
}