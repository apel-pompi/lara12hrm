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
        Schema::create('social_media_messages', function (Blueprint $table) {

            $table->id();

            /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

            $table->foreignId('conversation_id')
                ->constrained('social_media_conversations')
                ->cascadeOnDelete();

            $table->foreignId('contact_id')
                ->constrained('social_media_contacts')
                ->cascadeOnDelete();

            /*
    |--------------------------------------------------------------------------
    | Platform
    |--------------------------------------------------------------------------
    */

            $table->string('platform', 30);

            /*
    |--------------------------------------------------------------------------
    | Meta Message ID
    |--------------------------------------------------------------------------
    */

            $table->string('meta_message_id')->unique();

            /*
    |--------------------------------------------------------------------------
    | Direction
    |--------------------------------------------------------------------------
    */

            $table->enum(

                'direction',

                [

                    'inbound',

                    'outbound'

                ]

            );

            /*
    |--------------------------------------------------------------------------
    | Sender
    |--------------------------------------------------------------------------
    */

            $table->enum(

                'sender_type',

                [

                    'customer',

                    'agent',

                    'system'

                ]

            );

            /*
    |--------------------------------------------------------------------------
    | Type
    |--------------------------------------------------------------------------
    */

            $table->string(

                'message_type',

                30

            )->default('text');

            /*
    |--------------------------------------------------------------------------
    | Text
    |--------------------------------------------------------------------------
    */

            $table->longText('message')->nullable();

            /*
    |--------------------------------------------------------------------------
    | Attachment
    |--------------------------------------------------------------------------
    */

            $table->string('attachment')->nullable();

            $table->string('attachment_type')->nullable();

            $table->unsignedBigInteger('attachment_size')->nullable();

            /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

            $table->enum(

                'status',

                [

                    'received',

                    'sent',

                    'delivered',

                    'read',

                    'failed'

                ]

            )->default('received');

            /*
    |--------------------------------------------------------------------------
    | Meta Payload
    |--------------------------------------------------------------------------
    */

            $table->json('payload')->nullable();

            /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

            $table->timestamp('sent_at')->nullable();

            $table->timestamp('delivered_at')->nullable();

            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

            $table->index(

                [

                    'platform',

                    'conversation_id'

                ]

            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_messages');
    }
};
