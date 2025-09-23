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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trnname_id');
            $table->string('trncode');
            $table->unsignedBigInteger('branch_id');
            $table->tinyInteger('yearname');
            $table->tinyInteger('monthname');
            $table->tinyInteger('lastnumber')->default(0);
            $table->tinyInteger('increment')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('active')->default(0);
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('trnname_id')->references('id')->on('transaction_names')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
