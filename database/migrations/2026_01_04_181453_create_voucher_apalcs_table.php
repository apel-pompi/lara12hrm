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
        Schema::create('voucher_apalcs', function (Blueprint $table) {
            $table->id();
            $table->string('vouchernumber');
            $table->string('invnumber');
            $table->date('voucherdate');
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('currency')->nullable();
            $table->string('exchagerate')->nullable();
            $table->decimal('primeamt', 20, 3)->nullable();
            $table->decimal('baseamt', 20, 3)->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('vouchernumber')->references('vouchernumber')
                ->on('voucherheaders')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->foreign('invnumber')->references('vouchernumber')
                ->on('voucherheaders')->cascadeOnUpdate()->cascadeOnDelete();

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_apalcs');
    }
};
