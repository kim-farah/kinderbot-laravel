<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherLogSeeder extends Seeder
{
    public function run()
    {
        DB::table('teacher_activity_log')->insert([
            [
                'activity' => 'BUILD A ROBOT',
                'class' => 'KG1-A',
                'teacher' => 'Ms. Sara',
                'timestamp' => now(),
                'duration' => '30 min',
            ],
            [
                'activity' => 'SPINNING TOP',
                'class' => 'KG1-B',
                'teacher' => 'Ms. Sara',
                'timestamp' => now(),
                'duration' => '20 min',
            ],
        ]);
    }
}
