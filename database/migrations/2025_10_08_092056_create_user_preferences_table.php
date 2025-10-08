<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Rate
            $table->string('currency', 3)->default('PKR');     // PKR, USD, EUR, GBP, AED, INR
            $table->decimal('rate', 10, 2)->nullable();        // amount
            $table->string('unit', 16)->default('/hour');      // /hour | /day | /project

            // Availability
            $table->string('availability', 16)->default('now');       // now | 1week | 2weeks | 1month
            $table->string('hours_per_week', 16)->default('full-time'); // part-time | full-time | flexible

            // Toggles
            $table->boolean('remote_work')->default(true);
            $table->boolean('open_to_work')->default(true);
            $table->boolean('long_term')->default(false);

            $table->timestamps();

            $table->unique('user_id'); // 1 preference row per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
