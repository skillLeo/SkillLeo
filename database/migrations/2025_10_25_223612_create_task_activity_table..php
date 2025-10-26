<?php
// database/migrations/2025_01_01_000012_create_task_activity_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_activity', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();

            $table->foreignId('actor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('type', 64);
            // types:
            // comment, status_change, attachment_upload,
            // submitted_for_review, approved, change_requested,
            // reminder_sent, postponed, blocked

            $table->text('body')->nullable(); // free-form text / JSON summary

            $table->timestamps();

            $table->index(['task_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_activity');
    }
};
