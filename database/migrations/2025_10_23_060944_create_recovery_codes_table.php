<?php

 
// database/migrations/2025_01_15_000002_create_recovery_codes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recovery_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // Hashed code
            $table->string('plain_code')->nullable(); // For initial display only
            $table->boolean('used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'used']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recovery_codes');
    }
};