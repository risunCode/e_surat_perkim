<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reference_code_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('used_at');

            $table->index(['reference_code_id', 'user_id'], 'idx_ref_code_usage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_code_usage');
    }
};
