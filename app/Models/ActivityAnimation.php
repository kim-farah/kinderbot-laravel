<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityAnimation extends Model
{
    protected $fillable = ['activity_id', 'image_path', 'description', 'order'];
    
    public function activity() {
        return $this->belongsTo(Activity::class);
    }
}