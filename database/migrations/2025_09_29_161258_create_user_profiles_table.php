<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Location
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            
            // Skills, Experience, Portfolio (JSON)
            $table->json('skills')->nullable();
            $table->json('experience')->nullable();
            $table->json('portfolio')->nullable();
            $table->json('education')->nullable();
            
            // Preferences
            $table->string('currency')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->string('rate_unit')->nullable();
            $table->string('availability')->nullable();
            $table->string('hours_per_week')->nullable();
            
            // Toggles
            $table->boolean('remote_work')->default(false);
            $table->boolean('open_to_work')->default(false);
            $table->boolean('long_term')->default(false);
            $table->boolean('is_public')->default(true);
            
            // Onboarding
            $table->boolean('onboarding_completed')->default(false);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};