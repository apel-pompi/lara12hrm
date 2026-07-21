<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convert message_type enum -> varchar so we can store voice/file/etc.
        DB::statement("ALTER TABLE whatsapp_messages MODIFY message_type VARCHAR(50) NOT NULL DEFAULT 'text'");

        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->string('status')->default('sent')->after('direction'); // sent, delivered, read, failed
            $table->text('media_url')->nullable()->after('message');
            $table->string('media_mime')->nullable()->after('media_url');
            $table->integer('media_size')->nullable()->after('media_mime');
            $table->string('media_name')->nullable()->after('media_size');
            $table->unsignedBigInteger('reply_to')->nullable()->after('media_name');
            $table->timestamp('read_at')->nullable()->after('message_time');

            $table->foreign('reply_to')->references('id')->on('whatsapp_messages')->nullOnDelete();
        });

        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->integer('unread_count')->default(0)->after('is_read');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to']);
            $table->dropColumn([
                'status', 'media_url', 'media_mime', 'media_size', 'media_name', 'reply_to', 'read_at',
            ]);
        });

        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropColumn('unread_count');
        });

        DB::statement("ALTER TABLE whatsapp_messages MODIFY message_type ENUM('text','image','video','document','audio','location','sticker') NOT NULL DEFAULT 'text'");
    }
};
