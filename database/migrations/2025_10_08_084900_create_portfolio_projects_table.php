<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title', 120);
            $table->string('link_url', 512)->nullable();
            $table->string('image_path', 255)->nullable(); // storage path (public disk)
            $table->string('image_disk', 50)->default('public');
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('position')->default(0);
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_projects');
    }
};
