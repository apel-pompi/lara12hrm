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
        Schema::create('product_fees_dts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fees_hd_id');
            $table->unsignedBigInteger('fees_id');
            $table->decimal('amount',20,3);
            $table->integer('insqty');
            $table->decimal('totalamount',20,3);
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->foreign('fees_hd_id')->references('id')->on('product_fees_hds')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('fees_id')->references('id')->on('fees')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_fees_dts');
    }
};
