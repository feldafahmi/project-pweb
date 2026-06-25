<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * GET /api/profile
     *
     * Kembalikan data user yang sedang login. Dipakai mobile untuk
     * me-refresh nama/email/role agar sinkron dengan server.
     */
    public function show(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    /**
     * PUT /api/profile
     *
     * Perbarui nama/email. Ganti password bersifat opsional: bila
     * `password` dikirim, `current_password` wajib benar dan
     * `password_confirmation` harus cocok.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'  => 'required|string|min:3|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            // Password opsional — hanya divalidasi bila dikirim & tidak kosong.
            'current_password' => 'nullable|required_with:password|string',
            'password'         => 'nullable|string|min:8|confirmed',
        ]);

        // Jika user ingin ganti password, verifikasi password lama dulu.
        if (! empty($data['password'])) {
            if (! Hash::check($data['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Password saat ini salah.'],
                ]);
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user'    => $user,
        ]);
    }
}
