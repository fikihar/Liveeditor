<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function show(Assignment $assignment)
    {
        $siswa = auth()->user();

        // Pastikan tugas ini milik kelas siswa
        abort_if($assignment->class_id != $siswa->class_id, 403, 'Akses ditolak.');
        abort_if($assignment->status !== 'published', 404, 'Tugas belum dipublikasi.');
        
        // Cek tenggat waktu khusus tipe tugas
        if ($assignment->type === 'tugas' && $assignment->deadline && now()->gt($assignment->deadline)) {
            abort(403, 'Batas waktu pengerjaan tugas sudah habis!');
        }

        // Cari apakah siswa sudah pernah mengerjakan (draft/submitted)
        $submission = Submission::firstOrNew([
            'assignment_id' => $assignment->id,
            'student_id'    => $siswa->id,
        ]);

        // Jika baru pertama kali buka, isi dengan starter code
        if (!$submission->exists) {
            $submission->html_code = $assignment->starter_html;
            $submission->css_code  = $assignment->starter_css;
            $submission->status    = 'draft';
            $submission->save();
        }

        // Catat aktivitas buka editor
        ActivityLog::create([
            'student_id'    => $siswa->id,
            'assignment_id' => $assignment->id,
            'event'         => 'opened',
            'ip_address'    => request()->ip(),
        ]);

        return view('siswa.editor', compact('assignment', 'submission', 'siswa'));
    }

        public function logCheat(Assignment $assignment)
    {
        $siswa = auth()->user();
        if ($assignment->type === 'tugas') {
            $submission = Submission::where('assignment_id', $assignment->id)->where('student_id', $siswa->id)->first();
            if ($submission && $submission->status === 'draft') {
                $submission->increment('cheat_count');
            }
        }
        return response()->json(['success' => true]);
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $siswa = auth()->user();
        abort_if($assignment->class_id != $siswa->class_id, 403);

        $request->validate([
            'html_code' => 'nullable|string',
            'css_code'  => 'nullable|string',
            'action'    => 'required|in:save,submit'
        ]);

        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $siswa->id)
            ->firstOrFail();

        if ($assignment->type === 'tugas' && $submission->status === 'submitted') {
            return back()->with('error', 'Tugas sudah dikumpulkan dan tidak dapat diubah lagi.');
        }

        $submission->html_code = $request->html_code ?? '';
        $submission->css_code  = $request->css_code ?? '';
        
                if ($request->action === 'submit') {
            $submission->status = 'submitted';
            $submission->submitted_at = now();
            $msg = 'Tugas berhasil dikumpulkan!';

            // AUTO-GRADING ENGINE
            $criteria = $assignment->gradingCriteria;
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
                        $val = $crit->value ? preg_quote($crit->value, '/') : '';
                        // Cari tag target yang memiliki attribute value tersebut
                        if (preg_match('/<' . $target . '\b[^>]*' . $val . '[^>]*>/i', $html)) {
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
                        // Toleransi Whitespace: Hapus semua spasi
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

                // Normalisasi nilai ke skala 100 jika poin max != 100
                $maxPossible = $criteria->sum('points');
                if ($maxPossible > 0) {
                    $normalizedScore = round(($totalScore / $maxPossible) * 100);
                    $submission->score = $normalizedScore;
                } else {
                    $submission->score = 0;
                }
                $submission->grading_detail = json_encode($details);
            }
        } else {
            $submission->status = 'draft';
            $msg = 'Draft berhasil disimpan.';
        }
        
        $submission->save();

        ActivityLog::create([
            'student_id'    => $siswa->id,
            'assignment_id' => $assignment->id,
            'event'         => $request->action === 'submit' ? 'submit' : 'heartbeat',
            'ip_address'    => request()->ip(),
        ]);

                if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }
        return redirect()->route('siswa.dashboard')->with('success', $msg);
    }

    public function logActivity(Request $request, Assignment $assignment)
    {
        $siswa = auth()->user();
        if ($assignment->class_id != $siswa->class_id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $event = $request->input('event');
        if (in_array($event, ['tab_switch', 'focus_lost'])) {
            ActivityLog::create([
                'student_id'    => $siswa->id,
                'assignment_id' => $assignment->id,
                'event'         => $event,
                'ip_address'    => request()->ip(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}