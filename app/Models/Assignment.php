<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'type',
        'deadline',
        'starter_html',
        'starter_css',
        'has_css',
        'max_score',
        'is_graded',
        'status',
    ];

    protected $casts = [
        'deadline'   => 'datetime',
        'is_graded'  => 'boolean',
        'has_css'    => 'boolean',
    ];

    // Relasi: assignment milik satu kelas
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Relasi: assignment memiliki banyak kriteria penilaian
    public function gradingCriteria()
    {
        return $this->hasMany(GradingCriteria::class, 'assignment_id');
    }

    // Relasi: assignment memiliki banyak submission
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }

    // Relasi: activity logs terkait assignment ini
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'assignment_id');
    }

    // Scope: hanya yang sudah dipublish
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope: hanya latihan
    public function scopeLatihan($query)
    {
        return $query->where('type', 'latihan');
    }

    // Scope: hanya tugas
    public function scopeTugas($query)
    {
        return $query->where('type', 'tugas');
    }
}
