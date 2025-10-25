<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 
 
        
        return new class extends Migration
        {
            public function up()
            {
                Schema::create('clients', function (Blueprint $table) {
                    $table->id();
        
                    // Core relationships
                    $table->foreignId('user_id')
                        ->constrained()
                        ->cascadeOnDelete()
                        ->comment('The professional or seller who owns the project');
        
                    $table->foreignId('client_user_id')
                        ->nullable()
                        ->constrained('users')
                        ->cascadeOnDelete()
                        ->comment('The client user from users table');
        
                    // Business info
                    $table->string('company')->nullable();
                    $table->string('phone', 50)->nullable();
        
                    // Order/Project details
                    $table->decimal('order_value', 12, 2)->nullable();
                    $table->string('currency', 3)->default('PKR');
                    $table->string('payment_terms')->default('milestone'); // milestone, upfront50, monthly, etc.
                    $table->string('payment_status')->default('pending'); // pending, partial, paid
        
                    // Permissions
                    $table->boolean('portal_access')->default(true);
                    $table->boolean('can_comment')->default(false);
        
                    // Additional info
                    $table->text('special_requirements')->nullable();
                    $table->json('billing_address')->nullable();
                    $table->timestamp('contract_signed_at')->nullable();
        
                    // Priority
                    $table->string('priority')->default('medium'); // low, medium, high, urgent
        
                    // Meta
                    $table->timestamps();
                    $table->softDeletes();
        
                    // Indexes
                    $table->index(['user_id', 'client_user_id']);
                });
            }
        
            public function down()
            {
                Schema::dropIfExists('clients');
            }
        };
        
