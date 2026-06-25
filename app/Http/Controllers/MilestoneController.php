<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    /**
     * Get all milestones for authenticated user.
     */
    public function index()
    {
        $milestones = auth()->user()->milestones()->orderBy('created_at', 'asc')->get();
        return response()->json($milestones);
    }

    /**
     * Create a new milestone.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $milestone = auth()->user()->milestones()->create([
            'text' => $request->text,
            'completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'milestone' => $milestone,
        ]);
    }

    /**
     * Toggle milestone completion status.
     */
    public function toggle($id)
    {
        $milestone = auth()->user()->milestones()->findOrFail($id);
        $milestone->completed = !$milestone->completed;
        $milestone->save();

        return response()->json([
            'success' => true,
            'milestone' => $milestone,
        ]);
    }

    /**
     * Delete milestone.
     */
    public function destroy($id)
    {
        $milestone = auth()->user()->milestones()->findOrFail($id);
        $milestone->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
