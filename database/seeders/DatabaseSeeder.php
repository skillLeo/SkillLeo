<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed base/lookups first
        $this->call([
            SoftSkillSeeder::class,
        ]);
 
        $this->call([
            ProfileRelatedSeeder::class,          
            UserServicesAndReasonsSeeder::class,   
        ]);
    }
}
