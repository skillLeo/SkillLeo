<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->id();

            // Foreign key
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Device Information
            $table->string('device_name');
            $table->string('device_type'); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();

            // Network Information
            $table->string('ip_address', 45); // IPv4/IPv6
            $table->text('user_agent')->nullable();

            // Security Token
            $table->string('device_token', 60)->unique();

            // Usage Tracking
            $table->timestamp('last_used_at')->useCurrent();
            $table->timestamp('expires_at')->nullable(); // <-- allow null until you set it

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'expires_at']);
            $table->index('user_id');
            // no need to index device_token again; it's already UNIQUE (implicitly indexed)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trusted_devices');
    }
};
