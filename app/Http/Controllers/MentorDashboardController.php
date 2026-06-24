<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MentorDashboardController extends Controller
{
    /**
     * Display mentees under the mentor's guidance.
     */
    public function mentees()
    {
        $mentor = auth()->user();

        // Get mentees under guidance
        $mentees = $mentor->mentees()->get();

        return view('mentor.mentees', compact('mentees'));
    }
}
