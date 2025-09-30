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
        Schema::create('g_drives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('applcation_id');
            $table->unsignedBigInteger('stage_id');
            $table->string('docname')->nullable();
            $table->string('folder_id')->nullable();
            $table->string('file_id')->nullable();
            $table->string('file_url')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

             $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
             $table->foreign('applcation_id')->references('id')->on('student_applications')->onUpdate('cascade')->onDelete('cascade');
             $table->foreign('stage_id')->references('id')->on('workflow_stages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('g_drives');
    }
};
