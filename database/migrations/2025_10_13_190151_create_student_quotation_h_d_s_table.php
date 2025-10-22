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
        Schema::create('student_quotation_h_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_no');
            $table->foreignId('student_id')->constrained('students')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('sumamount', 20, 3)->nullable();
            $table->string('notes')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->date('adddate');
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('active')->default(0);
            $table->timestamps();
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_quotation_h_d_s');
    }
};
