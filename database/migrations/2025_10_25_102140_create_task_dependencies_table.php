<?php
// database/migrations/2024_01_01_000004_create_task_dependencies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('depends_on_task_id')->constrained('tasks')->onDelete('cascade');
            
            $table->timestamps();
            
            $table->unique(['task_id', 'depends_on_task_id']);
            $table->index('depends_on_task_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_dependencies');
    }
};