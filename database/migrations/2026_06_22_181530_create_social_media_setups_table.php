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
            $table->string('page_id')->unique();
            $table->string('access_token')->nullable();
            $table->string('verify_token')->nullable();
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
        Schema::dropIfExists('social_media_setups');
    }
};
