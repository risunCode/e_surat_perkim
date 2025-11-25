<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sent', 'completed', 'cancelled'])
                  ->default('draft')
                  ->after('type')
                  ->comment('Status surat: draft, sent, completed, cancelled');
            
            $table->boolean('is_completed')->default(false)->after('status')->comment('Simple completion flag');
            $table->timestamp('sent_at')->nullable()->after('is_completed');
            $table->timestamp('completed_at')->nullable()->after('sent_at');
            $table->text('status_note')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_completed', 'sent_at', 'completed_at', 'status_note']);
        });
    }
};
