<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\UserService;
use App\Models\UserReason;

class UserServicesAndReasonsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there's a user to attach to (use first user or create a demo)
        $user = User::first();
        if (!$user) {
            $now = Carbon::now()->format('YmdHis');
            $user = User::create([
                'name' => "Demo User",
                'email' => "demo.user+{$now}@example.com",
                'password' => Hash::make('password'), // change in production
            ]);
            $this->command->info("Created demo user id={$user->id}");
        }

        $userId = $user->id;
        $now = Carbon::now();

        // --- Services ---
        $services = [
            'Full-Stack Web Development',
            'REST API Development',
            'AI Chatbot Integration',
            'E-Commerce Solutions',
            'Performance Optimization',
        ];

        foreach ($services as $i => $title) {
            // Use updateOrCreate so seeder is idempotent
            UserService::updateOrCreate(
                ['user_id' => $userId, 'title' => $title],
                ['position' => $i, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        // --- Why Choose Me ---
        $reasons = [
            'I deliver clean, maintainable, and scalable code.',
            'Strong focus on performance and security.',
            'Excellent communication and timely delivery.',
            'Proven track record with Laravel & React projects.',
            'I treat every project as my own product.',
        ];

        foreach ($reasons as $i => $text) {
            UserReason::updateOrCreate(
                ['user_id' => $userId, 'text' => $text],
                ['position' => $i, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $this->command->info("Seeded services & reasons for user_id={$userId}");
    }
}
