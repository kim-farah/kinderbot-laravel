<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['class_id', 'title', 'order_index'];
    
    public function class() {
        return $this->belongsTo(ClassModel::class);
    }

    public function activities() {
        return $this->hasMany(Activity::class);
    }

    public function competencies() {
        return $this->hasMany(Competency::class);
    }
}
