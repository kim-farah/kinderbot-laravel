<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityCompletionStatus extends Model
{
    protected $fillable = ['description'];

    public function activityCompletion() {
        return $this->hasMany(ActivityCompletion::class);
    }
}