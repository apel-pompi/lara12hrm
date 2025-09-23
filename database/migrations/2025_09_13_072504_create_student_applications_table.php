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
        Schema::create('student_applications', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('partner_branch_id');
            $table->unsignedBigInteger('product_id');
            $table->string('stage')->nullable();
            $table->string('status')->nullable();
            $table->float('saleprice')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

             $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
             $table->foreign('workflow_id')->references('id')->on('workflows')->onUpdate('cascade')->onDelete('cascade');
             $table->foreign('partner_branch_id')->references('id')->on('partner_branches')->onUpdate('cascade')->onDelete('cascade');
             $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
             $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_applications');
    }
};
