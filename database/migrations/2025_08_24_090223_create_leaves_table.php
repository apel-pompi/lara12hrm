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
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id();
            $table->unsignedBigInteger('leaveplan_id');
            $table->unsignedBigInteger('empid');
            $table->date('fromdate');
            $table->date('todate'); 
            $table->integer('days');
            $table->unsignedBigInteger('substitute')->nullable();
            $table->string('reason');
            $table->tinyInteger('status')->default(0);  // 0=Pending, 1=Approved, 2=Rejected
            $table->timestamps();

            $table->foreign('leaveplan_id')->references('id')->on('leaveplans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('empid')->references('empid')->on('personal_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('substitute')->references('empid')->on('personal_infos')->onUpdate('cascade')->onDelete('set null');
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
