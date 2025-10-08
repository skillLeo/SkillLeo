<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('institution_id')->nullable(); // optional external ref
            $table->string('school', 180);    // e.g., Harvard University
            $table->string('degree', 160);    // e.g., Bachelor's, Master's
            $table->string('field', 160)->nullable(); // e.g., Computer Science

            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->boolean('is_current')->default(false);

            $table->unsignedSmallInteger('position')->default(0); // preserve order
            $table->timestamps();

            $table->index(['user_id', 'position']);
            $table->index(['school', 'degree']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educations');
    }
};
