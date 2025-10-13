<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('username', 50)->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
        
            $table->string('avatar_url')->nullable();
       
            $table->timestamp('email_verified_at')->nullable();
        
            $table->string('locale', 12)->default('en');
            $table->string('timezone', 64)->default('UTC');
            $table->string('is_active', 24)->default('active');
            $table->string('account_status')
            ->default('pending_onboarding')
            ->comment('pending_onboarding, onboarding_incomplete, onboarded, suspended');
            $table->string('is_profile_complete')->default('start');
            $table->string('is_public')->default('onboarding');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();

            $table->unsignedInteger('login_count')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
