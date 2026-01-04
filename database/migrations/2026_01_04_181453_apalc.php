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
        Schema::create('apalcs', function (Blueprint $table) {
            $table->id();
            $table->string('vouchernumber')->unique();
            $table->string('invnumber')->unique();
            $table->date('voucherdate');
            $table->string('currency')->nullable();
            $table->string('exchagerate')->nullable();
            $table->decimal('primeamt', 20, 3)->nullable();
            $table->decimal('baseamt', 20, 3)->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('apalcs');
    }
};
