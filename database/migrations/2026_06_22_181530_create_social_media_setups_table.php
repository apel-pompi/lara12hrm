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
        Schema::create('social_media_setups', function (Blueprint $table) {

            $table->id();

            $table->enum('platform', [

                'facebook',

                'messenger',

                'instagram',

                'whatsapp',

            ]);

            $table->string('page_id')->nullable();

            $table->string('phone_number_id')->nullable();

            $table->string('whatsapp_business_account_id')->nullable();

            $table->longText('access_token')->nullable();

            $table->string('verify_token')->nullable();
            $table->boolean('status')->default(true);
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_setups');
    }
};
