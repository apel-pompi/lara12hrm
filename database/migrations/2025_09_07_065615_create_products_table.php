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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('partner_id');
            $table->string('partner_branch_id')->nullable();
            $table->unsignedBigInteger('product_type_id');
            $table->tinyInteger('revinue_type')->default(0);
            $table->string('duration');
            $table->string('intak_month');
            $table->text('description')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('active')->default(0);
            $table->timestamps();

            $table->foreign('partner_id')->references('id')->on('partners')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_type_id')->references('id')->on('product_type_setups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
