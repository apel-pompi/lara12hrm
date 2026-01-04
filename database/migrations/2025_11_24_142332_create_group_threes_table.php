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
        Schema::create('group_threes', function (Blueprint $table) {
            $table->id();
            
            $table->integer('groupone');
            $table->integer('grouptwo');

            $table->string('groupthree')->unique();
            $table->string('description')->unique();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('groupone')
                ->references('groupone')
                ->on('group_ones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('grouptwo')
                ->references('grouptwo')
                ->on('group_twos')
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
        Schema::dropIfExists('group_threes');
    }
};