<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
    'chapter_id',
    'title',
    'objective',
    'instructions',
    'teacher_notes'
    ];

    public function chapter() {
        return $this->belongsTo(Chapter::class);
    }

    public function resources() {
        return $this->hasMany(ActivityResource::class);
    }

    public function completions() {
        return $this->hasMany(ActivityCompletion::class);
    }

    public function assessments() {
        return $this->hasMany(Assessment::class);
    }
}
