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
            $table->id();
            $table->unsignedBigInteger('empid')->unique();
            $table->string('empname');
            $table->date('joindate');
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('dept_id')->constrained('departments')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('des_id')->constrained('designations')
                ->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('personal_infos');
    }
};
