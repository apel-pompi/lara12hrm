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
        Schema::create('follow_up_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('follow_up_activity_id')
                ->constrained('follow_up_activities')
                ->cascadeOnDelete();
            $table->foreignId('follow_up_reminder_id')
                ->nullable()
                ->constrained('follow_up_reminders')
                ->nullOnDelete();
            $table->string('type', 50);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index([
                'user_id',
                'read_at',
            ]);
            $table->index([
                'user_id',
                'created_at',
            ]);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_notifications');
    }
};
