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
        Schema::create('whatsapp_form_setups', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('phone_id')->unique();
            $table->string('waba_id');
            $table->foreignId('team_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('counsilor_id')->nullable();
            $table->integer('status')->nullable()->comment('0 for inactive, 1 for active');
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
        Schema::dropIfExists('whatsapp_form_setups');
    }
};
