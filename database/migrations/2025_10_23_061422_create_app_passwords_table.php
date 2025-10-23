<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_passwords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Token Information
            $table->string('name'); // User-defined name
            $table->string('token', 64)->unique(); // Hashed token
            $table->text('plain_token')->nullable(); // For initial display only
            
            // Permissions/Scopes
            $table->json('scopes')->nullable();
            
            // Usage Tracking
            $table->timestamp('last_used_at')->nullable();
            $table->string('last_used_ip')->nullable();
            
            // Expiration
            $table->timestamp('expires_at')->nullable();
            $table->boolean('revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'revoked']);
            $table->index('token');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_passwords');
    }
};
