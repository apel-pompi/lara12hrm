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
        Schema::create('student_invoice_hd', function (Blueprint $table) {
            $table->id();
            $table->string('insnumber',20);
            $table->date('insdate');
            $table->foreignId('student_id')->constrained('students')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('payterms',20)->nullable(); //cash or credit
            $table->string('accountcode',50)->nullable();
            $table->string('chequeno',50)->nullable();
            $table->string('bankname')->nullable();
            $table->string('bankbranch')->nullable();
            $table->string('transno')->nullable();
            $table->string('currency',50)->default('BDT');
            $table->decimal('exchrate',20,2)->default(1.00);
            $table->text('note')->nullable();
            $table->decimal('totalamt',20,2);
            $table->decimal('disc_rate',20,2)->nullable();
            $table->decimal('disc_amt',20,2)->nullable();
            $table->decimal('netamount',20,2)->nullable();
            $table->tinyInteger('sign');
            $table->string('status',20)->nullable();
            $table->string('refe_code',20)->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->softDeletes();
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
        Schema::dropIfExists('student_invoice_hd');
    }
};
