<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('follow_up_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color', 30)->default('blue');
            $table->string('icon', 50)->nullable();
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->boolean('is_default')->default(false);
            $table->boolean('allow_reschedule')->default(true);
            $table->boolean('allow_edit')->default(true);
            $table->boolean('status')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_statuses');
    }
};
