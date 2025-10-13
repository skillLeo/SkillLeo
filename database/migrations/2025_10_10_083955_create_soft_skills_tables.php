
<?php

// database/migrations/2025_01_01_000001_create_soft_skills_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soft_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('icon', 50)->nullable();   // fa icon like 'comments', 'users', etc.
            $table->timestamps();

            $table->index('name');
        });

        Schema::create('user_soft_skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('soft_skill_id')
                ->constrained('soft_skills')
                ->cascadeOnDelete();

            $table->tinyInteger('level')->default(2)
                ->comment('1=Beginner, 2=Proficient, 3=Expert');

            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'soft_skill_id']);
            $table->index(['user_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_soft_skills');
        Schema::dropIfExists('soft_skills');
    }
};
