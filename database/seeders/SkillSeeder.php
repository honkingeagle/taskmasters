<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('skills')->insert([
            ['name' => 'SQL'],
            ['name' => 'Python'],
            ['name' => 'Calculus'],
            ['name' => 'Linear Algebra'],
            ['name' => 'Formatting'],
            ['name' => 'Editing'],
            ['name' => 'Copy Writing'],
            ['name' => 'Proof Reading'],
            ['name' => 'React Native'],
            ['name' => 'ReactJS'],
            ['name' => 'Redux'],
            ['name' => 'Scala'],
            ['name' => 'Data pipelines'],
            ['name' => 'Big Data'],
            ['name' => 'NoSQL'],
            ['name' => 'Database Design'],
            ['name' => 'AWS'],
            ['name' => 'Google Cloud'],
            ['name' => 'Data Warehouse'],
        ]);
    }
}
