<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('skill_id')
                ->constrained('skills')
                ->cascadeOnDelete();

            $table->tinyInteger('level')->default(2)
                ->comment('1=Beginner, 2=Proficient, 3=Expert');

            $table->unsignedSmallInteger('position')->default(0); // keeps user order
            $table->timestamps();

            $table->unique(['user_id', 'skill_id']);
            $table->index(['user_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
