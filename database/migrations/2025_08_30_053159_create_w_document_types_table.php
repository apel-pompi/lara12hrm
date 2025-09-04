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
        Schema::create('w_document_types', function (Blueprint $table) {
            $table->id();
            $table->string('docname');
            $table->date('adddate');
            $table->integer('totaluse');
            $table->unsignedBigInteger('user_id');
            $table->integer('active');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_document_types');
    }
};
