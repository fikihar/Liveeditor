<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$content = file_get_contents($file);

$autoGradeEngine = <<<PHP
        if (\$request->action === 'submit') {
            \$submission->status = 'submitted';
            \$submission->submitted_at = now();
            \$msg = 'Tugas berhasil dikumpulkan!';

            // AUTO-GRADING ENGINE
            \$criteria = \$assignment->gradingCriteria;
            if (\$criteria && \$criteria->count() > 0) {
                \$totalScore = 0;
                \$details = [];
                \$html = \$submission->html_code ?? '';
                \$css = \$submission->css_code ?? '';

                \$voidTags = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

                foreach (\$criteria as \$crit) {
                    \$point_awarded = 0;
                    \$note = 'Tidak memenuhi kriteria.';
                    \$target = preg_quote(\$crit->target, '/');

                    if (\$crit->type === 'has_tag') {
                        if (preg_match('/<' . \$target . '\b[^>]*>/i', \$html)) {
                            if (in_array(strtolower(\$crit->target), \$voidTags)) {
                                \$point_awarded = \$crit->points;
                                \$note = 'Berhasil (Tag lengkap).';
                            } else {
                                if (preg_match('/<\/' . \$target . '\s*>/i', \$html)) {
                                    \$point_awarded = \$crit->points;
                                    \$note = 'Berhasil (Tag pembuka & penutup lengkap).';
                                } else {
                                    \$point_awarded = floor(\$crit->points / 2);
                                    \$note = 'Kurang tepat (Ada pembuka, tapi tidak ada penutup). Poin dipotong 50%.';
                                }
                            }
                        } else {
                            \$note = 'Gagal: ' . \$crit->description;
                        }
                    } 
                    elseif (\$crit->type === 'has_attribute') {
                        \$val = \$crit->value ? preg_quote(\$crit->value, '/') : '';
                        // Cari tag target yang memiliki attribute value tersebut
                        if (preg_match('/<' . \$target . '\b[^>]*' . \$val . '[^>]*>/i', \$html)) {
                            \$point_awarded = \$crit->points;
                            \$note = 'Berhasil memenuhi atribut.';
                        } else {
                            \$note = 'Gagal: ' . \$crit->description;
                        }
                    } 
                    elseif (\$crit->type === 'has_text') {
                        \$searchVal = \$crit->value ?: \$crit->target;
                        if (stripos(\$html, \$searchVal) !== false) {
                            \$point_awarded = \$crit->points;
                            \$note = 'Berhasil (Teks ditemukan).';
                        } else {
                            \$note = 'Gagal: ' . \$crit->description;
                        }
                    } 
                    elseif (\$crit->type === 'has_css') {
                        // Toleransi Whitespace: Hapus semua spasi
                        \$cleanCss = preg_replace('/\s+/', '', \$css);
                        \$cleanTarget = preg_replace('/\s+/', '', \$crit->target);
                        \$cleanValue = \$crit->value ? preg_replace('/\s+/', '', \$crit->value) : '';
                        
                        \$regex = '/' . preg_quote(\$cleanTarget, '/') . '\{[^}]*' . preg_quote(\$cleanValue, '/') . '[^}]*\}/i';
                        if (preg_match(\$regex, \$cleanCss)) {
                            \$point_awarded = \$crit->points;
                            \$note = 'Berhasil menerapkan CSS.';
                        } else {
                            \$note = 'Gagal: ' . \$crit->description;
                        }
                    }

                    \$totalScore += \$point_awarded;
                    \$details[] = [
                        'type' => \$crit->type,
                        'target' => \$crit->target,
                        'description' => \$crit->description,
                        'points_max' => \$crit->points,
                        'points_awarded' => \$point_awarded,
                        'note' => \$note
                    ];
                }

                // Normalisasi nilai ke skala 100 jika poin max != 100
                \$maxPossible = \$criteria->sum('points');
                if (\$maxPossible > 0) {
                    \$normalizedScore = round((\$totalScore / \$maxPossible) * 100);
                    \$submission->score = \$normalizedScore;
                } else {
                    \$submission->score = 0;
                }
                \$submission->grading_detail = json_encode(\$details);
            }
        }
PHP;

$content = preg_replace('/if \(\$request->action === \'submit\'\) \{.*?\$msg = \'Tugas berhasil dikumpulkan!\';/s', $autoGradeEngine, $content);
file_put_contents($file, $content);
echo "Auto-Grader Engine injected.\n";
?>