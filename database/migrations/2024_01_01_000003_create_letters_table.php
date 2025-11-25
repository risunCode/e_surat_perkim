<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('agenda_number')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->date('letter_date')->nullable();
            $table->date('received_date')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('type')->default('incoming'); // incoming, outgoing
            $table->string('classification_code')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->timestamps();

            $table->index('type', 'idx_letters_type');
            $table->index('letter_date', 'idx_letters_letter_date');
            $table->index('user_id', 'idx_letters_user_id');
            $table->index('classification_code', 'idx_letters_classification_code');
            
            $table->foreign('classification_code')
                ->references('code')
                ->on('classifications')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
