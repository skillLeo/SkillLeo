<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Device identification
            $table->string('device_id', 64)->index();
            $table->string('device_name')->nullable();
            $table->string('device_type', 20)->default('desktop'); // desktop, mobile, tablet
            
            // Platform & Browser info
            $table->string('platform', 50)->nullable(); // Windows, macOS, iOS, Android, Linux
            $table->string('browser', 50)->nullable(); // Chrome, Safari, Firefox, Edge
            $table->string('browser_version', 20)->nullable();
            
            // Network info
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            // Security
            $table->boolean('is_trusted')->default(false);
            
            // Activity tracking
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            
            // Optional: Location data
            $table->string('location_country', 2)->nullable();
            $table->string('location_city', 100)->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'last_seen_at']);
            $table->index(['user_id', 'device_id']);
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};