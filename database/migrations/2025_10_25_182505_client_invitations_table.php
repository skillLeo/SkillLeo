<?php
// database/migrations/xxxx_xx_xx_create_client_invitations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_id')->constrained('users')->cascadeOnDelete();
            $table->string('email')->index();
            $table->string('name');
            $table->text('message')->nullable();
            $table->string('token', 64)->unique();
            $table->enum('status', ['pending', 'accepted', 'expired'])->default('pending')->index();
            $table->timestamp('expires_at')->index();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            
            $table->index(['email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_invitations');
    }
};