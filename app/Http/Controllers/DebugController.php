<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\User;
use App\Models\Package;

class DebugController extends Controller
{
    public function checkDb()
    {
        return response()->json([
            'status' => 'Success',
            'message' => 'GACOR KING!!!',
            'data' => [
                'videos' => Video::all(), // Mengambil data dari tabel VIDEO
                'users' => User::all(),   // Mengambil data dari tabel USERS
                'packages' => Package::all() // Mengambil data dari tabel PACKAGE
            ]
        ], 200);
    }

    public function checkMentor()
    {
        return response()->json([
            'status' => 'Success',
            'data' => [
                'mentors' => \App\Models\Mentor::all(), // panggil Mentor
                'users' => \App\Models\User::all()
            ]
        ], 200);
    }
}