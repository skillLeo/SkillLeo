<?php
// database/migrations/2024_01_01_000003_create_subtasks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->boolean('completed')->default(false);
            $table->date('postponed_until')->nullable();
            $table->timestamp('completed_at')->nullable();
      
            $table->integer('order')->default(0);
            
            $table->timestamps();
            
            $table->index('task_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subtasks');
    }
};