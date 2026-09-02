<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = auth()->user()->classes()->withCount('students')->get();
        return view('guru.kelas.index', compact('classes'));
    }

    public function create()
    {
        return view('guru.kelas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        auth()->user()->classes()->create($validated);

        return redirect()->route('guru.kelas.index')
            ->with('success', "Kelas {$validated['name']} berhasil dibuat!");
    }

    public function show(ClassRoom $kelas)
    {
        $this->authorizeClass($kelas);
        $students = $kelas->students()->withTrashed(false)->get();
        $assignments = $kelas->assignments()->latest()->get();
        return view('guru.kelas.show', compact('kelas', 'students', 'assignments'));
    }

    public function edit(ClassRoom $kela)
    {
        $this->authorizeClass($kela);
        return view('guru.kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, ClassRoom $kela)
    {
        $this->authorizeClass($kela);
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);
        $kela->update($validated);
        return redirect()->route('guru.kelas.index')
            ->with('success', "Kelas berhasil diperbarui!");
    }

    public function destroy(ClassRoom $kela)
    {
        $this->authorizeClass($kela);
        $kela->delete();
        return redirect()->route('guru.kelas.index')
            ->with('success', "Kelas berhasil dihapus.");
    }

    private function authorizeClass(ClassRoom $kelas): void
    {
        abort_if($kelas->guru_id !== auth()->id(), 403, 'Akses ditolak.');
    }

    public function destroyAll()
    {
        // Hapus semua kelas milik guru ini (Soft Delete)
        auth()->user()->classes()->delete();
        
        return redirect()->route('guru.kelas.index')
            ->with('success', "Semua kelas dan data di dalamnya berhasil dikosongkan.");
    }
}