<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('company', 180);
            $table->unsignedBigInteger('company_id')->nullable(); // optional link if you later add a companies table
            $table->string('title', 160);

            $table->unsignedTinyInteger('start_month')->nullable();   // 1..12
            $table->unsignedSmallInteger('start_year')->nullable();   // 1950..(now+6)
            $table->unsignedTinyInteger('end_month')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->boolean('is_current')->default(false);

            $table->string('location_city', 120)->nullable();
            $table->string('location_country', 120)->nullable();

            $table->text('description')->nullable();
            $table->unsignedSmallInteger('position')->default(0);     // ordering in UI
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
