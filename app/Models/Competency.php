<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    protected $fillable = ['activity_id', 'name', 'description'];
    
    public function chapter() {
        return $this->belongsTo(Activity::class);
    }

    public function assessments() {
        return $this->hasMany(Assessment::class);
    }
}
