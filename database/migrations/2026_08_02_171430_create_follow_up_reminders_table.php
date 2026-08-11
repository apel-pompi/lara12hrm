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
        Schema::create('follow_up_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follow_up_activity_id')
                ->constrained('follow_up_activities')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students');
            $table->foreignId('assigned_to')
                ->constrained('users');
            $table->dateTime('remind_at');
            $table->enum('channel', [
                'System',
                'Email',
                'SMS',
                'WhatsApp',
                'Messenger',
                'Push'
            ])->default('System');
            $table->enum('status', [
                'Pending',
                'Sent',
                'Failed',
                'Cancelled'
            ])->default('Pending');

            $table->boolean('is_sent')->default(false);

            $table->boolean('is_read')->default(false);

            $table->timestamp('sent_at')->nullable();

            $table->timestamp('read_at')->nullable();

            $table->text('error_message')->nullable();

            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_reminders');
    }
};
