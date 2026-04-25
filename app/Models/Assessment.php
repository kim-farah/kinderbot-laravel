<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
    'student_id',
    'teacher_id',
    'competency_id',
    'rating',
    'comments'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function competency() {
        return $this->belongsTo(Competency::class);
    }
}
