<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    public function run()
    {
        DB::table('sections')->insert([
            [
                'class_id' => 1,
                'section_name' => 'A',
                'teacher_id' => 1,
                'max_students' => 25,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'class_id' => 1,
                'section_name' => 'B',
                'teacher_id' => 2,
                'max_students' => 25,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'class_id' => 2,
                'section_name' => 'A',
                'teacher_id' => 2,
                'max_students' => 25,
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'class_id' => 3,
                'section_name' => 'A',
                'teacher_id' => 3,
                'max_students' => 25,
                'is_active' => true,
                'created_at' => now(),
            ],
        ]);
    }
}
