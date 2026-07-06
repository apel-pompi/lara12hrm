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
        Schema::create('attendance_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empid');
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('yearname');
            $table->integer('monthname');
            $table->string('workhour', 10);
            $table->string('totalhour', 10);
            $table->string('deducthour', 10)->nullable();
            $table->integer('absent')->nullable();
            $table->integer('leave')->nullable();
            $table->string('nethour', 10);
            $table->string('hrsurplus', 10)->nullable();
            $table->string('payablehour', 10)->nullable();
            $table->decimal('salary', 20, 2)->nullable();
            $table->decimal('payment', 20, 2)->nullable();
            $table->tinyInteger('active');
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->foreign('empid')
                ->references('empid')
                ->on('personal_infos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_statuses');
    }
};
