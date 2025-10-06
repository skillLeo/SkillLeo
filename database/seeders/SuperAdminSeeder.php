<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin if doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@skillleo.com'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => Hash::make('Admin@12345'), // Change this in production!
                'is_profile_complete' => 'completed',
                'email_verified_at' => now(),
                'status' => 'active',
            ]
        );

        $this->command->info('Super admin created successfully!');
        $this->command->info('Email: admin@skillleo.com');
        $this->command->info('Password: Admin@12345 (CHANGE THIS IN PRODUCTION!)');
    }
}