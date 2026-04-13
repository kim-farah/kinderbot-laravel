<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['class_id', 'teacher_id', 'name'];
    
    public function class() {
        return $this->belongsTo(ClassModel::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
}
