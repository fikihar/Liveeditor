<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

// Update STORE method
$newStore = <<<PHP
    public function store(Request \$request)
    {
        \$validated = \$request->validate([
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

        \$kelas = ClassRoom::findOrFail(\$validated['class_id']);
        abort_if(\$kelas->guru_id != auth()->id(), 403);

        \$validated["has_css"] = \$request->has("has_css");
        \$validated['status'] = 'published';
        
        \$assignment = Assignment::create(\$validated);

        if (\$request->has('criteria') && is_array(\$validated['criteria'])) {
            \$assignment->gradingCriteria()->createMany(\$validated['criteria']);
        }

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Tugas/Latihan beserta kriteria berhasil dibuat!');
    }
PHP;

$content = preg_replace('/public function store\(Request \$request\).*?with\(\'success\'.*?;.*?\}/s', $newStore, $content);

// Update UPDATE method
$newUpdate = <<<PHP
    public function update(Request \$request, Assignment \$tuga)
    {
        abort_if(\$tuga->classRoom->guru_id != auth()->id(), 403);

        \$validated = \$request->validate([
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

        \$validated["has_css"] = \$request->has("has_css");
        \$tuga->update(\$validated);

        // Update Criteria
        if (\$request->has('criteria') && is_array(\$validated['criteria'])) {
            \$existingIds = collect(\$validated['criteria'])->pluck('id')->filter()->toArray();
            
            // Delete removed criteria
            \$tuga->gradingCriteria()->whereNotIn('id', \$existingIds)->delete();

            // Update or Create
            foreach (\$validated['criteria'] as \$crit) {
                if (!empty(\$crit['id'])) {
                    \$tuga->gradingCriteria()->where('id', \$crit['id'])->update([
                        'type' => \$crit['type'],
                        'target' => \$crit['target'],
                        'value' => \$crit['value'],
                        'points' => \$crit['points'],
                        'description' => \$crit['description'],
                    ]);
                } else {
                    \$tuga->gradingCriteria()->create([
                        'type' => \$crit['type'],
                        'target' => \$crit['target'],
                        'value' => \$crit['value'],
                        'points' => \$crit['points'],
                        'description' => \$crit['description'],
                    ]);
                }
            }
        } else {
            // Delete all if none sent
            \$tuga->gradingCriteria()->delete();
        }

        return redirect()->route('guru.tugas.index')
            ->with('success', 'Info Tugas dan Kriteria berhasil diperbarui!');
    }
PHP;

$content = preg_replace('/public function update\(Request \$request, Assignment \$tuga\).*?with\(\'success\'.*?;.*?\}/s', $newUpdate, $content);

file_put_contents($file, $content);
echo "AssignmentController updated.\n";
?>