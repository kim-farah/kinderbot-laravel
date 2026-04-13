<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityCompletion extends Model
{
    protected $fillable = ['student_id', 'activity_id', 'completed_at'];
    
    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function activity() {
        return $this->belongsTo(Activity::class);
    }
}
