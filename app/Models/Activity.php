<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
    'class_id',
    'title',
    'objective',
    'materials',
    'overview',
    'skill_competencies',
    'rodin_comment',
    'activity_comment',
    'feedback_comment',
    'is_published',
    ];

    public function class() {
        return $this->belongsTo(ClassModel::class);
    }

    public function resources() {
        return $this->hasMany(Resource::class);
    }

    public function completions() {
        return $this->hasMany(ActivityCompletion::class);
    }

    public function assessments() {
        return $this->hasMany(Assessment::class);
    }

    public function steps()
    {
        return $this->hasMany(ActivityStep::class);
    }

    public function competencies()
    {
        return $this->hasMany(Competency::class);
    }

    public function animations()
    {
    return $this->hasMany(ActivityAnimation::class);
    }
    }
