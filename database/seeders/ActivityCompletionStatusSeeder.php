<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityCompletionStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('activity_completion_statuses')->insert([
            ['id' => 1, 'description' => 'Not Completed',],
            ['id' => 2, 'description' => 'Completed',]
        ]);
    }
}