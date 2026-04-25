<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'full_name', 'date_of_birth'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

    public function assessments() {
        return $this->hasMany(Assessment::class);
    }

    public function activityCompletions() {
        return $this->hasMany(ActivityCompletion::class);
    }

    public function studentParents() {
        return $this->hasMany(StudentParent::class);
    }
}
