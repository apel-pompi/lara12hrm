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
        Schema::create('partner_branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_name');
            $table->string('branch_email');
            $table->foreignId('partner_id')->constrained('partners')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('branch_state_id')->constrained('states')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('branch_city_id')->nullable()->constrained('cities')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('branch_phoneno')->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('active')->default(0);
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
        Schema::dropIfExists('partner_branches');
    }
};
