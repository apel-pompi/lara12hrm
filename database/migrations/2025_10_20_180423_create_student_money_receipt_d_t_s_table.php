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
        Schema::create('student_money_receipt_d_t_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insnumber_id')->constrained('student_invoice_hd')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('mrnumber_id')->constrained('student_invoice_hd')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('fees_id')->constrained('fees')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('amount', 20, 2)->default(0);
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
        Schema::dropIfExists('student_money_receipt_d_t_s');
    }
};
