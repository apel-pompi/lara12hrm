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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                ->nullable()
                ->constrained('whatsapp_conversations')
                ->nullOnDelete();
            $table->string('meta_message_id')->nullable();
            $table->enum('direction', ['incoming', 'outgoing']);
            $table->enum('message_type', ['text', 'image', 'video', 'document', 'audio', 'location', 'sticker'])->default('text');
            $table->longText('message')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('message_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
