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
        Schema::create('voucherdetails', function (Blueprint $table) {
            $table->id();
            $table->string('vouchernumber');
            $table->string('accountcode');
            $table->string('subacccode')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('exchagerate', 20, 3)->nullable();
            $table->decimal('primeamt', 20, 3)->nullable();
            $table->decimal('baseamt', 20, 3)->nullable();
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('voucherdetails');
    }
};
