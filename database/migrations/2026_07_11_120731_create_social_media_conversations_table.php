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
        Schema::create('social_media_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')
                ->constrained('social_media_contacts')
                ->cascadeOnDelete();

            $table->string('platform');

            $table->string('conversation_id')->nullable();

            $table->text('last_message')->nullable();

            $table->timestamp('last_message_at')->nullable();

            $table->integer('unread_count')->default(0);

            $table->boolean('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_conversations');
    }
};
