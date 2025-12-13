<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name'); // Simpan nama user untuk histori (jika user dihapus)
            $table->string('action'); // create, update, delete, toggle_completion, etc.
            $table->string('model_type'); // Letter, Disposition, Attachment, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('description'); // Deskripsi aksi dalam bahasa Indonesia
            $table->json('old_values')->nullable(); // Data sebelum perubahan
            $table->json('new_values')->nullable(); // Data setelah perubahan
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('action');
            $table->index('model_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
