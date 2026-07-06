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
        Schema::create('whats_apps_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('waba_id')->unique();
            $table->string('phone_id')->unique();
            $table->string('phoneno')->unique();
            $table->string('verified_name')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_apps_numbers');
    }
};
