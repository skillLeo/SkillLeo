<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')
                ->constrained('users')->cascadeOnDelete();

            // Core Info
            $table->string('title');
            $table->text('notes')->nullable();

            // Priority & Workflow
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('status', 32)->default('todo');

            // Workflow extensions
            $table->text('blocked_reason')->nullable()->after('status');
            $table->date('postponed_until')->nullable();
            $table->boolean('client_visible')->default(false);
            $table->boolean('requires_client_approval')->default(false);
            $table->timestamp('submitted_for_review_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('last_status_change_at')->nullable();

            // Task metadata
            $table->date('due_date')->nullable();
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->integer('story_points')->nullable();
            $table->integer('order')->default(0);
            $table->json('flags')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['project_id', 'status']);
            $table->index(['status', 'due_date']);
            $table->index(['assigned_to', 'status']);
            $table->index(['client_visible', 'requires_client_approval']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
