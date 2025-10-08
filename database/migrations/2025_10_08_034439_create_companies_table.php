<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Display name
            $table->string('wikidata_qid')->nullable()->index(); // e.g. Q95
            $table->string('country')->nullable();          // Country label string
            $table->string('country_code', 3)->nullable();  // Optional ISO-ish code (if you add later)
            $table->string('city')->nullable();             // HQ city (if available)
            $table->json('domains')->nullable();            // ["example.com"]
            $table->string('website')->nullable();          // canonical website
            $table->string('logo_url')->nullable();         // wikidata logo or favicon fallback
            $table->json('aliases')->nullable();            // alt names
            $table->timestamps();

            $table->index(['name']);
            // If on MySQL 8+: $table->fullText(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
