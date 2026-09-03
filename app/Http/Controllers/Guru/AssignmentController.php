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
        $assignments = Assignment::with(['classRoom' => function($q) { $q->withCount('students'); }])
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
            'criteria'     => 'nullable|array',
            'criteria.*.type' => 'required|string',
            'criteria.*.target' => 'required|string',
            'criteria.*.value' => 'nullable|string',
            'criteria.*.points' => 'required|integer',
            'criteria.*.description' => 'required|string',
        ]);

        $kelas = ClassRoom::findOrFail($validated['class_id']);
        abort_if($kelas->guru_id != auth()->id(), 403);

        $validated["has_css"] = $request->has("has_css");
        $validated['status'] = 'published';
        
        $assignment = Assignment::create($validated);

        if ($request->has('criteria') && is_array($validated['criteria'])) {
            $assignment->gradingCriteria()->createMany($validated['criteria']);
        }

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas/Latihan beserta kriteria berhasil dibuat!');
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
        abort_if($tuga->classRoom->guru_id != auth()->id(), 403);

        $validated = $request->validate([
            'title'        => 'required|string|max:150',
            'description'  => 'nullable|string',
            'deadline'     => 'nullable|date',
            'starter_html' => 'nullable|string',
            'starter_css'  => 'nullable|string',
            'criteria'     => 'nullable|array',
            'criteria.*.id' => 'nullable|exists:grading_criteria,id',
            'criteria.*.type' => 'required|string',
            'criteria.*.target' => 'required|string',
            'criteria.*.value' => 'nullable|string',
            'criteria.*.points' => 'required|integer',
            'criteria.*.description' => 'required|string',
        ]);

        $validated["has_css"] = $request->has("has_css");
        $tuga->update($validated);

        // Update Criteria
        if ($request->has('criteria') && is_array($validated['criteria'])) {
            $existingIds = collect($validated['criteria'])->pluck('id')->filter()->toArray();
            
            // Delete removed criteria
            $tuga->gradingCriteria()->whereNotIn('id', $existingIds)->delete();

            // Update or Create
            foreach ($validated['criteria'] as $crit) {
                if (!empty($crit['id'])) {
                    $tuga->gradingCriteria()->where('id', $crit['id'])->update([
                        'type' => $crit['type'],
                        'target' => $crit['target'],
                        'value' => $crit['value'],
                        'points' => $crit['points'],
                        'description' => $crit['description'],
                    ]);
                } else {
                    $tuga->gradingCriteria()->create([
                        'type' => $crit['type'],
                        'target' => $crit['target'],
                        'value' => $crit['value'],
                        'points' => $crit['points'],
                        'description' => $crit['description'],
                    ]);
                }
            }
        } else {
            // Delete all if none sent
            $tuga->gradingCriteria()->delete();
        }

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Info Tugas dan Kriteria berhasil diperbarui!');
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

    public function forceSubmit(Assignment $tuga)
    {
        $guru = auth()->user();
        abort_if($tuga->classRoom->guru_id != $guru->id, 403);
        
        $students = $tuga->classRoom->students;
        $count = 0;
        
        foreach ($students as $siswa) {
            $submission = \App\Models\Submission::firstOrCreate([
                'assignment_id' => $tuga->id,
                'student_id'    => $siswa->id
            ]);
            
            if ($submission->status !== 'submitted') {
                $submission->status = 'submitted';
                $submission->submitted_at = now();
                
                // AUTO-GRADING
                $criteria = $tuga->gradingCriteria;
                if ($criteria && $criteria->count() > 0) {
                    $totalScore = 0;
                    $details = [];
                    $html = $submission->html_code ?? '';
                    $css = $submission->css_code ?? '';
                    $voidTags = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

                    foreach ($criteria as $crit) {
                        $point_awarded = 0;
                        $note = 'Tidak memenuhi kriteria.';
                        $target = preg_quote($crit->target, '/');

                        if ($crit->type === 'has_tag') {
                            if (preg_match('/<' . $target . '\b[^>]*>/i', $html)) {
                                if (in_array(strtolower($crit->target), $voidTags)) {
                                    $point_awarded = $crit->points;
                                    $note = 'Berhasil (Tag lengkap).';
                                } else {
                                    if (preg_match('/<\/' . $target . '\s*>/i', $html)) {
                                        $point_awarded = $crit->points;
                                        $note = 'Berhasil (Tag pembuka & penutup lengkap).';
                                    } else {
                                        $point_awarded = floor($crit->points / 2);
                                        $note = 'Kurang tepat (Ada pembuka, tapi tidak ada penutup). Poin dipotong 50%.';
                                    }
                                }
                            } else {
                                $note = 'Gagal: ' . $crit->description;
                            }
                        } 
                        elseif ($crit->type === 'has_attribute') {
                            $attrOrTag = preg_quote($crit->target, '/');
                            $val = $crit->value ? preg_quote($crit->value, '/') : '';
                            
                            $isMatch = false;
                            // Skenario A: Target adalah nama Tag (misal: target='img', value='width="150"')
                            if (preg_match('/<' . $attrOrTag . '\b[^>]*' . $val . '[^>]*>/i', $html)) {
                                $isMatch = true;
                            }
                            // Skenario B: Target adalah nama Atribut (misal: target='width', value='150', atau target='alt')
                            elseif ($val !== '') {
                                if (preg_match('/\b' . $attrOrTag . '\s*=\s*["\']?' . $val . '["\']?/i', $html)) {
                                    $isMatch = true;
                                }
                            } else {
                                if (preg_match('/\b' . $attrOrTag . '\b/i', $html)) {
                                    $isMatch = true;
                                }
                            }

                            if ($isMatch) {
                                $point_awarded = $crit->points;
                                $note = 'Berhasil memenuhi atribut.';
                            } else {
                                $note = 'Gagal: ' . $crit->description;
                            }
                        } 
                        elseif ($crit->type === 'has_text') {
                            $searchVal = $crit->value ?: $crit->target;
                            if (stripos($html, $searchVal) !== false) {
                                $point_awarded = $crit->points;
                                $note = 'Berhasil (Teks ditemukan).';
                            } else {
                                $note = 'Gagal: ' . $crit->description;
                            }
                        } 
                        elseif ($crit->type === 'has_css') {
                            $cleanCss = preg_replace('/\s+/', '', $css);
                            $cleanTarget = preg_replace('/\s+/', '', $crit->target);
                            $cleanValue = $crit->value ? preg_replace('/\s+/', '', $crit->value) : '';
                            $regex = '/' . preg_quote($cleanTarget, '/') . '\{[^}]*' . preg_quote($cleanValue, '/') . '[^}]*\}/i';
                            if (preg_match($regex, $cleanCss)) {
                                $point_awarded = $crit->points;
                                $note = 'Berhasil menerapkan CSS.';
                            } else {
                                $note = 'Gagal: ' . $crit->description;
                            }
                        }

                        $totalScore += $point_awarded;
                        $details[] = [
                            'type' => $crit->type,
                            'target' => $crit->target,
                            'description' => $crit->description,
                            'points_max' => $crit->points,
                            'points_awarded' => $point_awarded,
                            'note' => $note
                        ];
                    }

                    $maxPossible = $criteria->sum('points');
                    if ($maxPossible > 0) {
                        $submission->score = round(($totalScore / $maxPossible) * 100);
                    } else {
                        $submission->score = 0;
                    }
                    $submission->grading_detail = json_encode($details);
                }
                
                $submission->save();
                $count++;
            }
        }
        
        return back()->with('success', "Berhasil menarik paksa (Force Submit) $count tugas siswa yang belum dikumpulkan.");
    }
}