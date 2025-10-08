<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experience_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained('experiences')->cascadeOnDelete();
            $table->string('name', 120);
            $table->unsignedTinyInteger('level')->default(2); // 1=Beginner, 2=Proficient, 3=Expert
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->index(['experience_id', 'position']);
            // No unique constraints -> duplicates allowed (as requested)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_skills');
    }
};
