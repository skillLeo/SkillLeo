<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_security', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->boolean('two_factor_email')->default(false);
            $table->boolean('two_factor_phone')->default(false);
            $table->boolean('two_factor_enabled')->default(false);

            $table->string('recovery_code')->nullable();
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'two_factor_enabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_security');
    }
};
