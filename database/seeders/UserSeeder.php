<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
{
    DB::table('users')->where('role_id', 1)->update([
        'email' => env('COORDINATOR_EMAIL', 'coordinator@test.com'),
        'password' => bcrypt(env('COORDINATOR_PASSWORD', 'demo123')),
        'updated_at' => now(),
    ]);
}
}
