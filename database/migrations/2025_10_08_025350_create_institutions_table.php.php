<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // Official display name
            $table->string('country')->nullable();       // e.g., "Pakistan"
            $table->string('country_code', 3)->nullable();// ISO-ish if available
            $table->string('city')->nullable();
            $table->json('domains')->nullable();         // ["ox.ac.uk"]
            $table->string('website')->nullable();       // primary website
            $table->string('logo_url')->nullable();      // favicon/logo (cached/external)
            $table->json('aliases')->nullable();         // other spellings / aka
            $table->enum('kind', ['university','college','school','online'])->nullable();
            $table->timestamps();

            $table->index(['name']);
            $table->index(['country_code']);
            // If you're on MySQL 8+, enable FULLTEXT for nicer ranking:
            // $table->fullText(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
