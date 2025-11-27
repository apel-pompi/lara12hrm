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
        Schema::create('work_hour_setups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('workhour');
            $table->year('yearname');
            $table->integer('monthname');
            $table->integer('active');
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
        Schema::dropIfExists('work_hour_setups');
    }
};
