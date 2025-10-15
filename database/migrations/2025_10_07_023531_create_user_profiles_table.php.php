<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Contact & Location
            $table->string('phone', 20)->nullable();
            $table->string('country', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('city', 120)->nullable();
            
            // Bio & Professional Info
            $table->string('tagline', 255)->nullable();
            $table->text('bio')->nullable();
            $table->string('banner')->nullable();
            $table->json('banner_preference')->nullable();
            
            $table->json('social_links')->nullable()->comment('LinkedIn, GitHub, Facebook, Twitter, etc.');
            $table->json('filter_preferences')->nullable();

            // Additional metadata
            $table->json('meta')->nullable();
            
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index(['country', 'state', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};