<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;

class ProfileRelatedSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $ts = time();
            $user = User::create([
                'name' => 'Hassam',
                'email' => "hassam.dev.571@gmail.com",
                'password' => Hash::make('aaaaaa'), // change after seeding
            ]);
        }
        $userId = $user->id;
        $now = Carbon::now();

        /*
         * --------------------------
         * 1) SKILLS + user_skills
         * --------------------------
         */
        $skillNames = [
            'PHP (Laravel)',
            'JavaScript',
            'React',
            'MySQL',
            'Docker'
        ];

        $skillIds = [];
        foreach ($skillNames as $i => $name) {
            $slug = Str::slug($name);
            $existing = DB::table('skills')->where('slug', $slug)->first();
            if ($existing) {
                $id = $existing->id;
                // update name if different
                DB::table('skills')->where('id', $id)->update(['name' => $name, 'updated_at' => $now]);
            } else {
                $id = DB::table('skills')->insertGetId([
                    'name' => $name,
                    'slug' => $slug,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $skillIds[] = $id;

            // upsert user_skills: leave existing but update position/level
            DB::table('user_skills')->updateOrInsert(
                ['user_id' => $userId, 'skill_id' => $id],
                [
                    'level' => [3,2,2,2,1][$i % 5], // variety of levels
                    'position' => $i,
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        }

        /*
         * --------------------------
         * 2) SOFT SKILLS + user_soft_skills
         * --------------------------
         */
        $soft = [
            ['slug' => 'communication', 'name' => 'Communication', 'icon' => 'comments'],
            ['slug' => 'teamwork', 'name' => 'Teamwork', 'icon' => 'users'],
            ['slug' => 'problem-solving', 'name' => 'Problem Solving', 'icon' => 'lightbulb'],
            ['slug' => 'time-management', 'name' => 'Time Management', 'icon' => 'clock'],
            ['slug' => 'creativity', 'name' => 'Creativity', 'icon' => 'palette'],
        ];

        $softSkillIds = [];
        foreach ($soft as $i => $s) {
            $existing = DB::table('soft_skills')->where('slug', $s['slug'])->first();
            if ($existing) {
                $softId = $existing->id;
                DB::table('soft_skills')->where('id', $softId)->update(['name' => $s['name'], 'icon' => $s['icon'], 'updated_at' => $now]);
            } else {
                $softId = DB::table('soft_skills')->insertGetId([
                    'name' => $s['name'],
                    'slug' => $s['slug'],
                    'icon' => $s['icon'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            $softSkillIds[] = $softId;

            // upsert user_soft_skills
            DB::table('user_soft_skills')->updateOrInsert(
                ['user_id' => $userId, 'soft_skill_id' => $softId],
                [
                    'level' => [2,2,3,2,2][$i % 5],
                    'position' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        /*
         * --------------------------
         * 3) user_languages
         * --------------------------
         */
        $languages = [
            ['name' => 'English', 'level' => 4],
            ['name' => 'Urdu', 'level' => 4],
            ['name' => 'Spanish', 'level' => 2],
            ['name' => 'French', 'level' => 2],
            ['name' => 'Arabic', 'level' => 1],
        ];

        foreach ($languages as $i => $lang) {
            DB::table('user_languages')->updateOrInsert(
                ['user_id' => $userId, 'name' => $lang['name']],
                [
                    'level' => $lang['level'],
                    'position' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        /*
         * --------------------------
         * 4) user_profiles (create or update)
         * --------------------------
         */
        $profileData = [
            'phone' => '+1 (555) 000-0000',
            'country' => 'United States',
            'state' => 'California',
            'city' => 'San Francisco',
            'headline' => 'Full Stack Developer · Laravel & React',
            'about' => 'I build maintainable and performant web applications. Experienced with Laravel, React, and cloud deployments.',
            'social_links' => json_encode([
                'linkedin' => 'https://linkedin.com/in/demo-user',
                'twitter' => 'https://twitter.com/demo_user',
                'facebook' => null,
                'instagram' => null,
            ]),
            'meta' => json_encode(['demo_profile' => true]),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Use updateOrInsert to be idempotent
        DB::table('user_profiles')->updateOrInsert(
            ['user_id' => $userId],
            $profileData
        );

        /*
         * --------------------------
         * 5) user_services (5 items)
         * --------------------------
         */
        $services = [
            'Web Application Development',
            'API & Microservices',
            'E-commerce Solutions',
            'Performance Audits',
            'CI/CD & DevOps Setup'
        ];

        $keptServiceIds = [];
        foreach ($services as $i => $title) {
            $titleClean = Str::of($title)->squish()->toString();
            $existing = DB::table('user_services')->where(['user_id' => $userId, 'title' => $titleClean])->first();
            if ($existing) {
                DB::table('user_services')->where('id', $existing->id)->update(['position' => $i, 'updated_at' => $now]);
                $keptServiceIds[] = $existing->id;
            } else {
                $id = DB::table('user_services')->insertGetId([
                    'user_id' => $userId,
                    'title' => $titleClean,
                    'position' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $keptServiceIds[] = $id;
            }
        }

        /*
         * --------------------------
         * 6) user_reasons (Why Choose Me) - 5 items
         * --------------------------
         */
        $reasons = [
            'I deliver clean, maintainable, and tested code.',
            'Reliable communication and on-time delivery.',
            'Strong focus on performance & security.',
            'Deep experience deploying production systems.',
            'I treat client projects like my own product.'
        ];

        $keptReasonIds = [];
        foreach ($reasons as $i => $text) {
            $textClean = Str::of($text)->squish()->toString();
            $existing = DB::table('user_reasons')->where(['user_id' => $userId, 'text' => $textClean])->first();
            if ($existing) {
                DB::table('user_reasons')->where('id', $existing->id)->update(['position' => $i, 'updated_at' => $now]);
                $keptReasonIds[] = $existing->id;
            } else {
                $id = DB::table('user_reasons')->insertGetId([
                    'user_id' => $userId,
                    'text' => $textClean,
                    'position' => $i,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $keptReasonIds[] = $id;
            }
        }

        $this->command->info("Seeded demo profile data for user_id={$userId}");
    }
}
