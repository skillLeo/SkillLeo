<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);                 // display name (original case)
            $table->string('slug', 120)->unique();       // dedupe key (lowercase/kebab)
            $table->timestamps();

            $table->index('name'); // quick lookups
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
