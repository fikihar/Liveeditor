<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user();
        $classes = $guru->classes()->withCount('students')->get();

        $totalSiswa = $guru->classes()->withCount('students')->get()->sum('students_count');
        $totalAssignment = $guru->classes()->with('assignments')->get()
            ->flatMap->assignments->count();
        $totalSubmission = Submission::whereIn(
            'assignment_id',
            $guru->classes()->with('assignments')->get()->flatMap->assignments->pluck('id')
        )->where('status', 'submitted')->count();

        return view('guru.dashboard', [
            'classes'         => $classes,
            'totalKelas'      => $classes->count(),
            'totalSiswa'      => $totalSiswa,
            'totalAssignment' => $totalAssignment,
            'totalSubmission' => $totalSubmission,
        ]);
    }
}