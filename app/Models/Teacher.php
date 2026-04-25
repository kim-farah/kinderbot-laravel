<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $fillable = ['user_id', 'full_name', 'phone', 'qualification', 'hire_date', 'email'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sections() {
        return $this->hasMany(Section::class);
    }

    public function assessments() {
        return $this->hasMany(Assessment::class);
    }
}
