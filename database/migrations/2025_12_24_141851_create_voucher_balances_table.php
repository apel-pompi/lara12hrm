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
        Schema::create('voucher_balances', function (Blueprint $table) {
            $table->id();
            $table->string('vouchernumber');
            $table->string('accountcode');
            $table->string('subacccode')->nullable();
            $table->date('voucherdate');
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->longText('referance')->nullable();
            $table->year('yearname');
            $table->integer('monthname');
            $table->string('currency')->nullable();
            $table->decimal('exchagerate', 20, 3)->nullable();
            $table->decimal('primeamt', 20, 3)->nullable();
            $table->decimal('baseamt', 20, 3)->nullable();
            $table->string('status')->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vouchernumber')->references('vouchernumber')
                ->on('voucherheaders')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->foreign('accountcode')->references('accountcode')
                ->on('chart_of_accounts')->cascadeOnUpdate()->cascadeOnDelete();
                
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_balances');
    }
};
