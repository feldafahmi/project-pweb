<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Controller WEB (Blade) untuk profil user. Endpoint API ada di
 * App\Http\Controllers\Api\ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user.
     */
    public function index()
    {
        return view('dashboard.profile.index');
    }

    /**
     * Perbarui informasi profil user.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
        ]);

        $user->update($data);

        return redirect()->route('dashboard.profile.index')->with('success', 'Informasi profil Anda berhasil diperbarui.');
    }

    /**
     * Tampilkan form ganti password.
     */
    public function passwordIndex()
    {
        return view('dashboard.profile.password');
    }

    /**
     * Perbarui password user.
     */
    public function passwordUpdate(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min'       => 'Password baru minimal 8 karakter.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'Password saat ini tidak cocok dengan data kami.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('dashboard.profile.password')->with('success', 'Password Anda berhasil diperbarui.');
    }
}
