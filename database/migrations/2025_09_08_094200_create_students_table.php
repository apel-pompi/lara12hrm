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
            $table->string('ename')->nullable(); //Phone
            $table->string('ephone')->nullable(); //Phone

            $table->string('contactpre')->nullable(); //Contact Preference
            $table->foreignId('preaddcountry')->nullable()->constrained('countries')->nullable()
                ->cascadeOnUpdate()->cascadeOnDelete(); //Permanent Address Country ID
            $table->foreignId('preaddstate')->nullable()->constrained('states')->nullable()
                ->cascadeOnUpdate()->cascadeOnDelete(); //Permanent Address State ID
            $table->foreignId('preaddcity')->nullable()->constrained('cities')
                ->cascadeOnUpdate()->cascadeOnDelete(); //Permanent Address City ID

            $table->string('paddress')->nullable(); //Physical Address 
            $table->date('intakedate')->nullable(); //Preferred Intake
            $table->string('pascountry')->nullable(); //Country of Passport
            $table->string('pasnocountry')->nullable(); //Passport Number
            $table->string('visatype')->nullable(); //Visa Type
            $table->date('visaexdate')->nullable(); //Visa Expiry Date 
            $table->string('pvisades')->nullable(); //Previous Visas & Destination 

            $table->foreignId('descountry_id')->nullable()->constrained('countries')
                ->cascadeOnUpdate()->cascadeOnDelete(); //preferred destination

            $table->foreignId('stage_id')->nullable()->constrained('student_stages')
                ->cascadeOnUpdate()->cascadeOnDelete(); //Student Stage ID

            $table->string('metting_note')->nullable(); //Metting Note
            $table->string('passportno')->nullable(); //Student Passport No
            $table->foreignId('assain_user')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('source_id')->constrained('student_sources')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('photo')->nullable(); //Photo
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

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
