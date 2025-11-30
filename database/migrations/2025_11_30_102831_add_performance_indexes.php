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
        // Add missing indexes for letters table
        Schema::table('letters', function (Blueprint $table) {
            $table->index('is_completed', 'idx_letters_is_completed');
            $table->index('reference_to', 'idx_letters_reference_to');
            $table->index(['type', 'is_completed'], 'idx_letters_type_completed');
            $table->index(['user_id', 'type'], 'idx_letters_user_type');
        });

        // Add missing indexes for dispositions table
        Schema::table('dispositions', function (Blueprint $table) {
            $table->index('letter_status', 'idx_dispositions_letter_status');
            $table->index(['letter_id', 'letter_status'], 'idx_dispositions_letter_status_combo');
            $table->index('due_date', 'idx_dispositions_due_date');
        });

        // Add missing indexes for attachments table  
        Schema::table('attachments', function (Blueprint $table) {
            $table->index('extension', 'idx_attachments_extension');
            $table->index('mime_type', 'idx_attachments_mime_type');
            $table->index(['letter_id', 'extension'], 'idx_attachments_letter_ext');
        });

        // Add missing indexes for notifications table
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('type', 'idx_notifications_type');
            $table->index(['user_id', 'type'], 'idx_notifications_user_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropIndex('idx_letters_is_completed');
            $table->dropIndex('idx_letters_reference_to');
            $table->dropIndex('idx_letters_type_completed');
            $table->dropIndex('idx_letters_user_type');
        });

        Schema::table('dispositions', function (Blueprint $table) {
            $table->dropIndex('idx_dispositions_letter_status');
            $table->dropIndex('idx_dispositions_letter_status_combo');
            $table->dropIndex('idx_dispositions_due_date');
        });

        Schema::table('attachments', function (Blueprint $table) {
            $table->dropIndex('idx_attachments_extension');
            $table->dropIndex('idx_attachments_mime_type');
            $table->dropIndex('idx_attachments_letter_ext');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_type');
            $table->dropIndex('idx_notifications_user_type');
        });
    }
};
