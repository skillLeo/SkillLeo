<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portfolio_project_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_project_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('image_path', 255);
            $table->string('image_disk', 50)->default('public');
            $table->unsignedSmallInteger('position')->default(0);
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['portfolio_project_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_project_media');
    }
};
