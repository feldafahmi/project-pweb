<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * Perbarui nama/email. Klien mobile mengirim `name` tunggal — dipetakan ke
     * first_name/last_name. Ganti password opsional: bila `password` dikirim,
     * `current_password` wajib benar dan `password_confirmation` harus cocok.
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
            'current_password' => 'nullable|required_with:password|string',
            'password'         => 'nullable|string|min:8|confirmed',
        ]);

        if (! empty($data['password'])) {
            if (! $user->password || ! Hash::check($data['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Password saat ini salah.'],
                ]);
            }
            $user->password = Hash::make($data['password']);
        }

        // Petakan `name` tunggal ke first_name/last_name (skema DB).
        $parts = preg_split('/\s+/', trim($data['name']), 2);
        $user->first_name = $parts[0];
        $user->last_name  = $parts[1] ?? '';
        $user->email      = $data['email'];
        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user'    => $user,
        ]);
    }
}
