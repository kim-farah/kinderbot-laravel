<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        DB::table('activities')->insert([
            [
                'title' => 'BUILD A ROBOT',
                'class_id' => 1,
                'objective' => 'Students will learn basic robotics concepts',
                'materials_needed' => 'LEGO kit, scissors, glue',
                'instructions' => 'Step 1: Build the robot. Step 2: Hold the handle. Step 3: Turn the handle.',
                'estimated_duration' => 30,
                'difficulty_level' => 'medium',
                'is_published' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'SPINNING TOP',
                'class_id' => 1,
                'objective' => 'Students will learn about rotation and colors',
                'materials_needed' => 'Spinner, coloring pencils',
                'instructions' => 'Step 1: Build the spinner. Step 2: Decorate. Step 3: Spin it!',
                'estimated_duration' => 20,
                'difficulty_level' => 'easy',
                'is_published' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'DIRECTION CAR',
                'class_id' => 2,
                'objective' => 'Students will learn about directions and spatial awareness',
                'materials_needed' => 'LEGO car kit, direction cards',
                'instructions' => 'Step 1: Build the car. Step 2: Follow direction cards.',
                'estimated_duration' => 25,
                'difficulty_level' => 'medium',
                'is_published' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
