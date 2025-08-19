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
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id();
            $table->unsignedBigInteger('empid');
            $table->string('empname');
            $table->date('joindate');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('dept_id');
            $table->unsignedBigInteger('des_id');
            $table->date('dateofbirth');
            $table->integer('gender');
            $table->string('present');
            $table->string('permanent');
            $table->string('phonepersonal');
            $table->string('phoneoffice')->nullable();
            $table->string('email')->nullable();
            $table->string('blood',50);
            $table->string('nidpass');
            $table->string('photo');
            $table->integer('active')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dept_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('des_id')->references('id')->on('designations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
