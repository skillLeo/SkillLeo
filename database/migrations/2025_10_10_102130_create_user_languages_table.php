<?php

// database/migrations/2025_01_01_000000_create_user_languages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name', 60); // English, Spanish, etc.
            // 1=Basic, 2=Intermediate, 3=Fluent, 4=Native
            $table->tinyInteger('level')->default(2);
            $table->unsignedSmallInteger('position')->default(0);

            $table->timestamps();

            $table->unique(['user_id', 'name']);           // prevent duplicates per user
            $table->index(['user_id', 'position']);        // quick ordering
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_languages');
    }
};
