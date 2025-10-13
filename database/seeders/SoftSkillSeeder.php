<?php

// database/seeders/SoftSkillSeeder.php
namespace Database\Seeders;

use App\Models\SoftSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SoftSkillSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            ['slug'=>'communication','name'=>'Communication','icon'=>'comments'],
            ['slug'=>'leadership','name'=>'Leadership','icon'=>'users'],
            ['slug'=>'teamwork','name'=>'Teamwork','icon'=>'handshake'],
            ['slug'=>'problem-solving','name'=>'Problem Solving','icon'=>'lightbulb'],
            ['slug'=>'creativity','name'=>'Creativity','icon'=>'palette'],
            ['slug'=>'time-management','name'=>'Time Management','icon'=>'clock'],
            ['slug'=>'adaptability','name'=>'Adaptability','icon'=>'sync'],
            ['slug'=>'critical-thinking','name'=>'Critical Thinking','icon'=>'brain'],
            ['slug'=>'attention-to-detail','name'=>'Attention to Detail','icon'=>'search'],
            ['slug'=>'organization','name'=>'Organization','icon'=>'list'],
            ['slug'=>'collaboration','name'=>'Collaboration','icon'=>'users-cog'],
            ['slug'=>'emotional-intelligence','name'=>'Emotional Intelligence','icon'=>'heart'],
            ['slug'=>'decision-making','name'=>'Decision Making','icon'=>'balance-scale'],
            ['slug'=>'conflict-resolution','name'=>'Conflict Resolution','icon'=>'handshake-angle'],
            ['slug'=>'negotiation','name'=>'Negotiation','icon'=>'handshake-simple'],
            ['slug'=>'presentation','name'=>'Presentation','icon'=>'chalkboard-user'],
            ['slug'=>'public-speaking','name'=>'Public Speaking','icon'=>'microphone'],
            ['slug'=>'active-listening','name'=>'Active Listening','icon'=>'ear-listen'],
            ['slug'=>'empathy','name'=>'Empathy','icon'=>'hands-holding-heart'],
            ['slug'=>'self-motivation','name'=>'Self-Motivation','icon'=>'rocket'],
            ['slug'=>'work-ethic','name'=>'Work Ethic','icon'=>'briefcase'],
            ['slug'=>'flexibility','name'=>'Flexibility','icon'=>'arrows-spin'],
            ['slug'=>'resilience','name'=>'Resilience','icon'=>'shield-heart'],
            ['slug'=>'initiative','name'=>'Initiative','icon'=>'flag'],
            ['slug'=>'strategic-thinking','name'=>'Strategic Thinking','icon'=>'chess'],
            ['slug'=>'analytical-skills','name'=>'Analytical Skills','icon'=>'chart-line'],
            ['slug'=>'customer-service','name'=>'Customer Service','icon'=>'headset'],
            ['slug'=>'project-management','name'=>'Project Management','icon'=>'tasks'],
            ['slug'=>'multitasking','name'=>'Multitasking','icon'=>'layer-group'],
            ['slug'=>'mentoring','name'=>'Mentoring','icon'=>'user-graduate'],
        ];

        // add timestamps for upsert
        $rows = array_map(fn($r) => $r + ['created_at'=>$now,'updated_at'=>$now], $rows);

        // idempotent
        SoftSkill::upsert($rows, ['slug'], ['name','icon','updated_at']);
    }
}
