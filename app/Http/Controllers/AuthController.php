<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // =====================================================================
    //  API (klien mobile) — autentikasi berbasis token Sanctum, stateless.
    //  Skema DB memakai first_name/last_name/username; API menerima `name`
    //  tunggal lalu memetakannya agar app mobile tidak perlu diubah.
    // =====================================================================

    // REGISTER (API) — buat akun lalu langsung terbitkan token (auto-login)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|min:3|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Model memetakan `name` → first_name/last_name & meng-auto-generate username.
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user',
        ]);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message'      => 'Registrasi Berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }

    // LOGIN (API) — verifikasi kredensial tanpa session web (stateless token)
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! $user->password || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau Password salah'],
            ]);
        }

        // Satu user = satu token aktif.
        $user->tokens()->delete();
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login Berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    // GOOGLE SIGN-IN (API) — verifikasi ID token, lalu find-or-create user.
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
        $fullName = $payload['name'] ?? ($email ? explode('@', $email)[0] : 'User Mark-Up');
        $avatar   = $payload['picture'] ?? null;

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
                $user->google_id = $googleId;
                $user->avatar ??= $avatar;
                $user->save();
            } else {
                $user = User::create([
                    'name'      => $fullName,
                    'email'     => $email,
                    'google_id' => $googleId,
                    'avatar'    => $avatar,
                    'password'  => null,
                    'role'      => 'user',
                ]);
            }
        }

        $user->tokens()->delete();
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login Google Berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    // LOGOUT (API) — hapus token yang dipakai untuk request ini
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil, token telah dihapus',
        ], 200);
    }

    // =====================================================================
    //  WEB (Blade, session-based) — dibuat felda untuk dashboard web.
    // =====================================================================

    public function webLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return response()->json([
                'message' => 'Login Berhasil',
                'name'    => $user->name,
                'email'   => $user->email,
                'role'    => $user->role,
            ], 200);
        }

        return response()->json([
            'message' => 'Email atau Password salah.',
        ], 422);
    }

    public function webRegister(Request $request)
    {
        $request->validate([
            'username'    => 'required|string|min:3|max:50|unique:users,username',
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'institution' => 'nullable|string|max:255',
            'password'    => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'username'    => $request->username,
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'institution' => $request->institution,
            'password'    => Hash::make($request->password),
            'role'        => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Registrasi Berhasil',
            'name'    => $user->name,
            'email'   => $user->email,
            'role'    => $user->role,
        ], 201);
    }

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
