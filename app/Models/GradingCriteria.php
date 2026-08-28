<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'type',
        'target',
        'value',
        'description',
        'points',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
