<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{

    protected $fillable = ['program_id', 'name', 'grade_level', 'order_index', 'is_active'];

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function sections() {
        return $this->hasMany(Section::class);
    }

    public function chapters() {
        return $this->hasMany(Chapter::class);
    }

}
