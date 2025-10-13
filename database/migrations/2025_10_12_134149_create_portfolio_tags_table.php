<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create portfolio_tags table to store all unique tags
        Schema::create('portfolio_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->integer('usage_count')->default(0); // Track how many projects use this tag
            $table->timestamps();
        });

        // Create pivot table for portfolio-tag relationship
        Schema::create('portfolio_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('portfolio_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['portfolio_id', 'portfolio_tag_id']);
        });

        // Create user_filter_preferences table to store which tags user wants to display
        Schema::create('user_filter_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('visible_tags')->nullable(); // Array of tag IDs to display (max 5)
            $table->integer('max_visible')->default(5);
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_tag');
        Schema::dropIfExists('user_filter_preferences');
        Schema::dropIfExists('portfolio_tags');
    }
};