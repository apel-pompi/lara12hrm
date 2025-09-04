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
        Schema::create('w_document_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('doctype_id');
            $table->unsignedBigInteger('workstage_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('active');
            $table->timestamps();
            $table->foreign('workflow_id')->references('id')->on('workflows')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('doctype_id')->references('id')->on('w_document_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('workstage_id')->references('id')->on('workflow_stages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_document_checks');
    }
};
