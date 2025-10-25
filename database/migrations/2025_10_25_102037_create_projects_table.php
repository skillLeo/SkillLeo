<?php
// database/migrations/2024_01_01_000001_create_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Project Owner
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('name');
            $table->string('key', 10)->unique();
            $table->enum('type', ['scrum', 'kanban', 'waterfall', 'custom']);
            $table->string('category')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('description')->nullable();
            
            $table->date('start_date');
            $table->date('due_date');
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('currency', 3)->default('PKR');
            $table->decimal('estimated_hours', 8, 2)->nullable();
            
            $table->json('flags')->nullable();
            $table->enum('status', ['planning', 'active', 'on-hold', 'completed', 'cancelled'])->default('planning');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};