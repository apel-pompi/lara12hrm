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
        Schema::create('holiday_dts', function (Blueprint $table) {
            $table->id();
            $table->date('holidate');
            $table->string('holitypes');
            $table->foreignId('holihd_id')->constrained('holiday_hds')
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
        Schema::dropIfExists('holiday_dts');
    }
};
