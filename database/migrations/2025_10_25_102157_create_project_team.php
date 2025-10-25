<?php
// database/migrations/2024_01_01_000005_create_project_team_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('role');
            $table->string('tech_stack');
            $table->string('position');
            
            $table->timestamps();
            
            $table->unique(['project_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_team');
    }
};