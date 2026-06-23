<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
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

        // Get mentees with milestones
        $mentees = $mentor->mentees()->with('milestones')->get();

        return view('mentor.mentees', compact('mentees'));
    }

    /**
     * Save/update feedback for a specific milestone.
     */
    public function giveFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'nullable|string',
        ]);

        $milestone = Milestone::findOrFail($id);

        // Security check: ensure milestone belongs to a mentee of this mentor
        $mentor = auth()->user();
        $isOwnMentee = $mentor->mentees()->where('users.id', $milestone->user_id)->exists();

        if (!$isOwnMentee) {
            abort(403, 'Unauthorized. Ini bukan mahasiswa bimbingan Anda.');
        }

        $milestone->feedback = $request->feedback;
        $milestone->save();

        return redirect()->back()->with('success', 'Feedback berhasil disimpan.');
    }
}
