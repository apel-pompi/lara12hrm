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
        Schema::create('codes_params', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('code')->nullable();
            $table->string('accdisc')->nullable();
            $table->string('cracc')->nullable();
            $table->string('dracc')->nullable();
            $table->string('props')->nullable(); //addition + minus -
            $table->integer('percent')->nullable();
            $table->string('acctax')->nullable();
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('active');
            $table->timestamps();

            $table->foreign('cracc')->references('accountcode')
                ->on('chart_of_accounts')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('dracc')->references('accountcode')
                ->on('chart_of_accounts')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->foreign('acctax')->references('accountcode')
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
        Schema::dropIfExists('codes_params');
    }
};
