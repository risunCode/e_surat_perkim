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
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('letters')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // 'transcript', 'letter', etc.
            $table->string('signature_hash', 64)->unique(); // SHA-256 hash
            $table->text('content_hash'); // Hash of document content
            $table->timestamp('signed_at');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Store additional info like letter details
            $table->boolean('is_valid')->default(true);
            $table->timestamps();
            
            $table->index(['signature_hash', 'is_valid']);
            $table->index(['letter_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_signatures');
    }
};
