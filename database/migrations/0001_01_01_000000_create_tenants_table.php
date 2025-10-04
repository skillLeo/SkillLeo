<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('plan')->default('starter'); // starter|pro|agency|enterprise
            $table->unsignedSmallInteger('seats_limit')->default(1);
            $table->string('status')->default('active'); // active|suspended
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tenants');
    }
};
