<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $guruId = auth()->id();
        // Ambil semua tugas milik kelas yang diajar oleh guru ini
        $assignments = Assignment::with(['classRoom'])
            ->whereHas('classRoom', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })
            ->withCount('submissions')
            ->latest()
            ->get();

        return view('guru.tugas.index', compact('assignments'));
    }

    public function create()
    {
        // Hanya tampilkan kelas milik guru ini
        $classes = auth()->user()->classes()->orderBy('name')->get();
        return view('guru.tugas.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'title'        => 'required|string|max:150',
            'description'  => 'nullable|string',
            'type'         => 'required|in:latihan,tugas',
            'deadline'     => 'nullable|date',
            'starter_html' => 'nullable|string',
            'starter_css'  => 'nullable|string',
                    ]);

        // Pastikan kelas tersebut benar-benar milik guru ini
        $kelas = ClassRoom::findOrFail($validated['class_id']);
        abort_if($kelas->guru_id != auth()->id(), 403);

        $validated["has_css"] = $request->has("has_css");
        $validated['status'] = 'published';
        $assignment = Assignment::create($validated);

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas/Latihan berhasil dibuat!');
    }

    public function show(Assignment $tuga) // Laravel resource auto-names parameter 'tuga' (singular of tugas)
    {
        $this->authorizeAssignment($tuga);
        
        $tuga->load(['classRoom.students', 'submissions.student']);
        
        return view('guru.tugas.show', ['assignment' => $tuga]);
    }

    public function edit(Assignment $tuga)
    {
        $this->authorizeAssignment($tuga);
        $classes = auth()->user()->classes()->orderBy('name')->get();
        return view('guru.tugas.edit', ['assignment' => $tuga, 'classes' => $classes]);
    }

    public function update(Request $request, Assignment $tuga)
    {
        $this->authorizeAssignment($tuga);

        $validated = $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'title'        => 'required|string|max:150',
            'description'  => 'nullable|string',
            'type'         => 'required|in:latihan,tugas',
            'deadline'     => 'nullable|date',
            'starter_html' => 'nullable|string',
            'starter_css'  => 'nullable|string',
                    ]);

        // Pastikan kelas tujuan masih milik guru ini
        $kelas = ClassRoom::findOrFail($validated['class_id']);
        abort_if($kelas->guru_id != auth()->id(), 403);

        $validated["has_css"] = $request->has("has_css");
        $validated['status'] = 'published';
        $tuga->update($validated);

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Data tugas berhasil diperbarui!');
    }

    public function destroy(Assignment $tuga)
    {
        $this->authorizeAssignment($tuga);
        $tuga->delete();
        
        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas berhasil dihapus!');
    }

    private function authorizeAssignment(Assignment $assignment): void
    {
        $assignment->loadMissing('classRoom');
        abort_if($assignment->classRoom->guru_id != auth()->id(), 403, 'Akses ditolak.');
    }

    // FITUR KOREKSI (MANUAL GRADING)
    public function koreksi(\App\Models\Assignment $tuga, \App\Models\User $siswa)
    {
        $this->authorizeAssignment($tuga);
        
        // Pastikan siswa ini ada di kelas yang sama dengan tugas
        abort_if($siswa->class_id !== $tuga->class_id, 403, 'Siswa tidak berada di kelas ini.');

        $submission = \App\Models\Submission::where('assignment_id', $tuga->id)
            ->where('student_id', $siswa->id)
            ->first();

        // Jika siswa belum mengerjakan sama sekali (belum ada submission)
        if (!$submission) {
            return redirect()->route('guru.tugas.show', $tuga)->with('error', 'Siswa belum membuka atau mengerjakan tugas ini.');
        }

        return view('guru.tugas.koreksi', [
            'assignment' => $tuga,
            'siswa' => $siswa,
            'submission' => $submission
        ]);
    }

    public function simpanNilai(Request $request, \App\Models\Assignment $tuga, \App\Models\User $siswa)
    {
        $this->authorizeAssignment($tuga);
        
        if ($tuga->type === 'latihan') {
            return back()->with('error', 'Latihan tidak perlu dinilai.');
        }

        $request->validate([
            'score' => 'required|integer|min:0|max:100'
        ]);

        $submission = \App\Models\Submission::where('assignment_id', $tuga->id)
            ->where('student_id', $siswa->id)
            ->firstOrFail();

        if ($submission->status === 'draft') {
            return back()->with('error', 'Tugas masih berupa draft dan belum dikumpulkan siswa.');
        }
            
        $submission->score = $request->score;
        $submission->save();

        return redirect()->route('guru.tugas.show', $tuga)->with('success', 'Nilai untuk ' . $siswa->name . ' berhasil disimpan!');
    }
}
