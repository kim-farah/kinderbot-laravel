<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $fillable = ['user_id', 'full_name'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sections() {
        return $this->hasMany(Section::class);
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }
}
