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
        Schema::table('attack_alerts', function (Blueprint $table) {
            // Add status column: active, mitigated, archived
            $table->string('status')->default('active')->after('details');
            $table->text('mitigation_notes')->nullable()->after('status');
            $table->foreignId('mitigated_by')->nullable()->after('mitigation_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('mitigated_at')->nullable()->after('mitigated_by');
            $table->foreignId('archived_by')->nullable()->after('mitigated_at')->constrained('users')->nullOnDelete();
            $table->timestamp('archived_at')->nullable()->after('archived_by');
            
            $table->index('status');
        });

        // Migrate existing data: is_dismissed = true -> status = 'archived'
        DB::table('attack_alerts')
            ->where('is_dismissed', true)
            ->update(['status' => 'archived', 'archived_at' => DB::raw('dismissed_at'), 'archived_by' => DB::raw('dismissed_by')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attack_alerts', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropConstrainedForeignId('mitigated_by');
            $table->dropConstrainedForeignId('archived_by');
            $table->dropColumn(['status', 'mitigation_notes', 'mitigated_at', 'archived_at']);
        });
    }
};
