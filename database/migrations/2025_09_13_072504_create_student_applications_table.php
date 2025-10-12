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
        Schema::create('student_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('workflow_id')->constrained('workflows')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('partner_branch_id')->constrained('partner_branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('workflow_stages')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('status')->nullable();
            $table->float('saleprice')->nullable();
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
        Schema::dropIfExists('student_applications');
    }
};
