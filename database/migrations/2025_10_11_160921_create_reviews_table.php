<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Support;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('client_name');
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->text('content');
            $table->string('image_path')->nullable();
            $table->string('image_disk')->default('public');
            $table->integer('position')->default(0);
            $table->timestamps();
            
            $table->index(['user_id', 'position']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};