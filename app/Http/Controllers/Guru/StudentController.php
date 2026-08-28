<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(ClassRoom $kelas)
    {
        $this->authorizeClass($kelas);
        $students = $kelas->students()->orderBy('name')->get();
        return view('guru.siswa.index', compact('kelas', 'students'));
    }

    public function create(ClassRoom $kelas)
    {
        $this->authorizeClass($kelas);
        return view('guru.siswa.create', compact('kelas'));
    }

    public function store(Request $request, ClassRoom $kelas)
    {
        $this->authorizeClass($kelas);
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:20|unique:users,username',
            'password' => 'nullable|string|min:6',
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password'] ?? 'smk1234'),
            'role'     => 'siswa',
            'class_id' => $kelas->id,
        ]);

        return redirect()->route('guru.siswa.index', $kelas)
            ->with('success', "Siswa {$validated['name']} berhasil ditambahkan!");
    }

    public function edit(ClassRoom $kelas, User $siswa)
    {
        $this->authorizeClass($kelas);
        return view('guru.siswa.edit', compact('kelas', 'siswa'));
    }

    public function update(Request $request, ClassRoom $kelas, User $siswa)
    {
        $this->authorizeClass($kelas);
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:20|unique:users,username,' . $siswa->id,
            'password' => 'nullable|string|min:6',
        ]);

        $siswa->name     = $validated['name'];
        $siswa->username = $validated['username'];
        if (!empty($validated['password'])) {
            $siswa->password = Hash::make($validated['password']);
        }
        $siswa->save();

        return redirect()->route('guru.siswa.index', $kelas)
            ->with('success', "Data siswa berhasil diperbarui!");
    }

    public function destroy(ClassRoom $kelas, User $siswa)
    {
        $this->authorizeClass($kelas);
        $siswa->delete();
        return redirect()->route('guru.siswa.index', $kelas)
            ->with('success', "Siswa berhasil dihapus.");
    }

    public function import(Request $request, ClassRoom $kelas)
    {
        $this->authorizeClass($kelas);
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new StudentsImport($kelas->id), $request->file('file'));
            return redirect()->route('guru.siswa.index', $kelas)
                ->with('success', 'Siswa berhasil diimpor dari Excel!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    private function authorizeClass(ClassRoom $kelas): void
    {
        abort_if($kelas->guru_id !== auth()->id(), 403, 'Akses ditolak.');
    }
}