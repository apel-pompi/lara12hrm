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
        Schema::create('student_quoation_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_hd_id')->constrained('student_quotation_h_d_s')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('fee_id')->constrained('fees')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('amount', 20, 2)->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('student_quoation_fees');
    }
};
