<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'class_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Cek apakah user adalah guru
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    // Cek apakah user adalah siswa
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // Relasi: guru memiliki banyak kelas
    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'guru_id');
    }

    // Relasi: siswa terdaftar di satu kelas
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Relasi: siswa memiliki banyak submission
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    // Relasi: siswa memiliki banyak activity log
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'student_id');
    }
}
