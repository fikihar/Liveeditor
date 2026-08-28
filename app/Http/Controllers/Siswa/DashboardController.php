<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user();
        
        // Perbaiki pemanggilan query agar tidak error
        $assignments = collect();
        if ($siswa->class_id) {
            $assignments = \App\Models\Assignment::where('class_id', $siswa->class_id)
                ->published()
                ->orderBy('created_at', 'desc')
                ->with(['submissions' => function($query) use ($siswa) { $query->where('student_id', $siswa->id); }])->get();
        }

        return view('siswa.dashboard', compact('assignments', 'siswa'));
    }
}