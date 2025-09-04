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
        Schema::create('product_type_setups', function (Blueprint $table) {
            $table->id();
            $table->string('producttypename');
            $table->unsignedBigInteger('mastercaterory_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('active');
            $table->timestamps();

            $table->foreign('mastercaterory_id')->references('id')->on('master_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_type_setups');
    }
};
