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
        Schema::create('product_fees_dts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fees_hd_id')->constrained('product_fees_hds')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('fees_id')->constrained('fees')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('amount',20,2);
            $table->integer('insqty');
            $table->string('pay_type')->nullable();
            $table->decimal('totalamount',20,2);
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
        Schema::dropIfExists('product_fees_dts');
    }
};
