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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('workflow_id');
            $table->unsignedBigInteger('master_cat_id');
            $table->unsignedBigInteger('partner_type_id');
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('brn')->nullable();
            $table->string('currency')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('photo')->nullable();
            $table->string('partner_branch_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('active')->default(0);
            $table->timestamps();

            $table->foreign('master_cat_id')->references('id')->on('master_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('partner_type_id')->references('id')->on('partner_type_setups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
