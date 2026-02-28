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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupone')->constrained('group_ones')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('grouptwo')->constrained('group_twos')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('groupthree')->constrained('group_threes')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('accountcode')->unique();
            $table->string('description')->unique();
            $table->string('accounttype',50);
            $table->string('accountusage',50);
            $table->string('analyticalcode',50)->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('active');
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
        Schema::dropIfExists('chart_of_accounts');
    }
};