<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'coordinator'],
            ['id' => 2, 'name' => 'teacher'],
            ['id' => 3, 'name' => 'parent'],
        ]);
    }
}
