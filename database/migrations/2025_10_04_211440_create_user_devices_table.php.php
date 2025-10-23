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
            $table->string('device_id', 64)->index(); // remove global unique()
            $table->string('device_name')->nullable();
            $table->string('device_type', 20)->default('desktop'); // desktop, mobile, tablet

            // Platform & Browser info
            $table->string('platform', 50)->nullable(); 
            $table->string('browser', 50)->nullable();
            $table->string('browser_version', 20)->nullable();

            // Network info
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Security
            $table->boolean('is_trusted')->default(false);
            $table->timestamp('revoked_at')->nullable(); // missing in your version

            // Activity tracking
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();

            // Optional: Location data
            $table->string('location_country', 2)->nullable();
            $table->string('location_city', 100)->nullable();

            $table->timestamps();

            // ✅ Correct indexing
            $table->unique(['user_id', 'device_id']); // composite unique key
            $table->index(['user_id', 'last_seen_at']);
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
