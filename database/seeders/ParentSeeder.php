<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParentSeeder extends Seeder
{
    public function run()
    {
        DB::table('parents')->insert([
            [
                'user_id' => 3,
                'full_name' => 'Ali Nassour',
                'phone' => '0123456789',
                'email' => 'ali@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'full_name' => 'Rana Fadel',
                'phone' => '0987654321',
                'email' => 'rana@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
