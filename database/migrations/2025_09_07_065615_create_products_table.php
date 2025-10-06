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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('partner_id')->constrained('partners')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_type_id')->constrained('product_type_setups')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('revinue_type')->default(0);
            $table->string('duration');
            $table->string('intak_month');
            $table->text('description')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('products');
    }
};
