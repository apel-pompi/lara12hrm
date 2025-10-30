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
        Schema::create('product_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('degree_id')->constrained('academics')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('scoretype',50);
            $table->float('score');
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
        Schema::dropIfExists('product_requirements');
    }
};
