<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'guru_id',
        'description',
    ];

    // Relasi: kelas dimiliki oleh guru
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi: kelas memiliki banyak siswa
    public function students()
    {
        return $this->hasMany(User::class, 'class_id')->where('role', 'siswa');
    }

    // Relasi: kelas memiliki banyak tugas/latihan
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }
}
