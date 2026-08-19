<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('follow_up_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->foreignId('conversation_id')
                ->nullable()
                ->constrained('social_media_conversations')
                ->nullOnDelete();
            $table->foreignId('follow_up_master_id')
                ->constrained('follow_up_masters')
                ->restrictOnDelete();
            $table->foreignId('follow_up_status_id')
                ->constrained('follow_up_statuses')
                ->restrictOnDelete();
            $table->foreignId('assigned_to')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->string('title', 255);

            $table->text('description')->nullable();

            $table->date('follow_up_date');

            $table->time('follow_up_time')->nullable();

            $table->enum('priority', [
                'Low',
                'Medium',
                'High',
                'Urgent'
            ])->default('Medium');

            $table->enum('status', [
                'Pending',
                'Completed',
                'Cancelled',
                'Rescheduled',
                'Missed'
            ])->default('Pending');

            $table->text('remarks')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_auto')->default(false);

            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index([
                'student_id',
                'status'
            ]);

            $table->index([
                'assigned_to',
                'status'
            ]);

            $table->index([
                'follow_up_date',
                'follow_up_time'
            ]);

            $table->index('follow_up_master_id');

            $table->index('follow_up_status_id');

            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_activities');
    }
};
