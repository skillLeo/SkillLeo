    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('login_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                
                // Login Information
                $table->string('ip_address');
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('browser')->nullable();
                $table->string('platform')->nullable();
                $table->text('user_agent')->nullable();
                
                // Status
                $table->enum('status', ['success', 'failed', '2fa_required', '2fa_failed'])->default('success');
                $table->string('failure_reason')->nullable();
                
                // 2FA Information
                $table->string('two_factor_method')->nullable(); // authenticator, email, sms, recovery_code
                $table->boolean('trusted_device')->default(false);
                
                $table->timestamp('logged_in_at');
                $table->timestamps();

                // Indexes
                $table->index(['user_id', 'logged_in_at']);
                $table->index(['user_id', 'status']);
                $table->index('logged_in_at');
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('login_history');
        }

    };
