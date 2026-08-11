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
        Schema::create('follow_up_masters', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            // CALL, EMAIL, WHATSAPP, MESSENGER, VISIT
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->unsignedInteger('default_days')->default(0);
            $table->enum('default_priority', [
                'Low',
                'Medium',
                'High',
                'Urgent'
            ])->default('Medium');
            $table->boolean('status')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('icon', 50)->nullable();
            $table->string('color', 30)->default('blue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_up_masters');
    }
};
