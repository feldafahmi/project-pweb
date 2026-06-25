<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display users management page.
     */
    public function adminIndex(Request $request)
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Update user role dynamically.
     */
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'role' => 'required|in:admin,mentor,user',
        ]);

        $user->update([
            'role' => $data['role'],
        ]);

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
