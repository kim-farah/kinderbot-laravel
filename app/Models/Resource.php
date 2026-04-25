<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['activity_id', 'file_path', 'title'];
    
    public function activity() {
        return $this->belongsTo(Activity::class);
    }
}
