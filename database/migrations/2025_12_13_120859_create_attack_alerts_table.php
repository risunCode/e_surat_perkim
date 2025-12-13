<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attack_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('attack_type'); // xss, sqli, bruteforce, idor, etc
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('fingerprint')->nullable();
            $table->string('target_url');
            $table->string('target_field')->nullable();
            $table->text('payload')->nullable();
            $table->text('details')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_dismissed')->default(false);
            $table->foreignId('dismissed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_dismissed', 'created_at']);
            $table->index('attack_type');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attack_alerts');
    }
};
