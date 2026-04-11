<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        DB::table('programs')->insert([
            [
                'id' => 1,
                'name' => 'Mindscape Kinderbot',
                'description' => 'Kinderbot Learning Platform Program',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
