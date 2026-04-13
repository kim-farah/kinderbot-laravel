<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    protected $fillable = ['chapter_id', 'name', 'description'];
    
    public function chapter() {
        return $this->belongsTo(Chapter::class);
    }

    public function assessments() {
        return $this->hasMany(Assessment::class);
    }
}
