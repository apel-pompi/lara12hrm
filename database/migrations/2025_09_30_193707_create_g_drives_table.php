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
        Schema::create('g_drives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('applcation_id')->constrained('student_applications')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('workflow_stages')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('docname')->nullable();
            $table->string('folder_id')->nullable();
            $table->string('file_id')->nullable();
            $table->string('file_url')->nullable();
            $table->foreignId('user_id')->constrained('users')
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
        Schema::dropIfExists('g_drives');
    }
};
