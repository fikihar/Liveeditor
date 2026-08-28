<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel Presence untuk tugas (CCTV Guru)
Broadcast::channel('assignment.{assignmentId}', function ($user, $assignmentId) {
    $assignment = \App\Models\Assignment::find($assignmentId);
    if (!$assignment) return false;

    // Jika guru, pastikan ini kelasnya
    if ($user->role == 'guru') {
        if ($assignment->classRoom->guru_id == $user->id) {
            return ['id' => $user->id, 'name' => $user->name, 'role' => 'guru'];
        }
        return false;
    }

    // Jika siswa, pastikan kelasnya sama
    if ($user->role == 'siswa') {
        if ($user->class_id == $assignment->class_id) {
            return ['id' => $user->id, 'name' => $user->name, 'role' => 'siswa'];
        }
        return false;
    }

    return false;
});