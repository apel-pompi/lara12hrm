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
        Schema::create('user_wise_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('facebook_forms')
                ->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('user_wise_forms');
    }
};
