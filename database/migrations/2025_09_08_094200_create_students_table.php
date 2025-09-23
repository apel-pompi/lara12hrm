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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->nullable();; //Student ID 
            $table->string('fname'); //Student First Name 
            $table->string('lname'); //Student Last Name
            $table->date('dateofbirth')->nullable(); //Date of Birth
            $table->tinyInteger('gender')->nullable(); //0 man 1 woman 2 other's
            $table->string('email')->nullable(); //Email
            $table->string('phone')->nullable(); //Phone
            $table->string('contactpre')->nullable(); //Contact Preference
            $table->unsignedBigInteger('preaddcountry')->nullable(); //Permanent Address Country ID
            $table->unsignedBigInteger('preaddstate')->nullable(); //Permanent Address State ID
            $table->unsignedBigInteger('preaddcity')->nullable(); //Permanent Address City ID
            $table->string('paddress')->nullable(); //Physical Address 
            $table->date('intakedate')->nullable(); //Preferred Intake
            $table->string('pascountry')->nullable(); //Country of Passport
            $table->string('pasnocountry')->nullable(); //Passport Number
            $table->string('visatype')->nullable(); //Visa Type
            $table->date('visaexdate')->nullable(); //Visa Expiry Date 
            $table->string('pvisades')->nullable(); //Previous Visas & Destination 
            $table->unsignedBigInteger('descountry_id')->nullable(); //preferred destination?
            $table->unsignedBigInteger('stage_id')->nullable(); //Student Stage ID
            $table->string('metting_note')->nullable(); //Metting Note
            $table->string('passportno')->nullable(); //Student Passport No
            $table->unsignedBigInteger('assain_user'); //User ID
            $table->unsignedBigInteger('source_id'); //Student Source ID
            $table->string('photo')->nullable(); //Photo
            $table->unsignedBigInteger('user_id'); //Student Source ID
            $table->tinyInteger('status')->nullable();
            $table->timestamps();

            $table->foreign('preaddcountry')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('preaddstate')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('preaddcity')->references('id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('descountry_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('stage_id')->references('id')->on('student_stages')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('assain_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('source_id')->references('id')->on('student_sources')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
