<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    public function classes() {
        return $this->hasMany(ClassModel::class);
    }
}
