<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ProgramSeeder::class,
            ClassSeeder::class,
            TeacherSeeder::class,
            ParentSeeder::class,
            SectionSeeder::class,
            ActivitySeeder::class,
            TeacherLogSeeder::class,
        ]);
    }
}
