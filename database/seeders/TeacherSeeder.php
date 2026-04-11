<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        DB::table('teachers')->insert([
            [
                'user_id' => 2,
                'full_name' => 'Sara Harb',
                'phone' => '0123456789',
                'qualification' => 'Bachelor of Education',
                'hire_date' => '2024-01-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'full_name' => 'Maryam Obeid',
                'phone' => '0987654321',
                'qualification' => 'Bachelor of Science',
                'hire_date' => '2024-03-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'full_name' => 'Leila Saifi',
                'phone' => '0555123456',
                'qualification' => 'Master of Education',
                'hire_date' => '2024-02-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
