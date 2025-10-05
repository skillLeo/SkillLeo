<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('oauth_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // google, github, linkedin
            $table->string('provider_uid');
            $table->string('email')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('profile')->nullable();
            $table->timestamps();
            
            $table->unique(['provider', 'provider_uid']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('oauth_identities');
    }
};