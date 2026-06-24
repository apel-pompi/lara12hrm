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
        Schema::create('inactive_lead_transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->json('student_ids')->nullable();
            $table->unsignedInteger('student_count')->default(0);
            $table->foreignId('from_user_id')->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('to_user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('transferred_by_user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('period')->nullable();
            $table->string('transfer_type')->default('selected');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inactive_lead_transfer_logs');
    }
};
