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
        Schema::create('partner_branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_name');
            $table->string('branch_email');
            $table->unsignedBigInteger('branch_state_id');
            $table->unsignedBigInteger('branch_city_id');
            $table->string('branch_phoneno');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('active')->default(0);
            $table->timestamps();
            $table->foreign('branch_state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branch_city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_branches');
    }
};
