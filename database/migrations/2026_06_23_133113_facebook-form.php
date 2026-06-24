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
        Schema::create('facebook_forms', function (Blueprint $table) {
            $table->id();
            $table->string('facebook_form_id')->unique();
            $table->string('form_name');
            $table->string('status')->nullable();
            $table->dateTime('created_time')->nullable();
            $table->string('page_id')->nullable();
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
        Schema::dropIfExists('facebook_forms');
    }
};
