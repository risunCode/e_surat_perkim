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
        Schema::table('users', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('role');
            $table->string('security_question')->nullable()->after('birth_date');
            $table->string('security_answer')->nullable()->after('security_question');
            $table->boolean('security_setup_completed')->default(false)->after('security_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'security_question', 'security_answer', 'security_setup_completed']);
        });
    }
};
