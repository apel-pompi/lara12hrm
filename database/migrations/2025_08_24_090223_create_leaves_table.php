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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leaveplan_id')->constrained('leaveplans')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('empid')->constrained('personal_infos')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('fromdate');
            $table->date('todate'); 
            $table->integer('days');
            $table->foreignId('substitute')->constrained('personal_infos')->nullable()
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('reason');
            $table->tinyInteger('status')->default(0);  // 0=Pending, 1=Approved, 2=Rejected
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
        Schema::dropIfExists('leaves');
    }
};
