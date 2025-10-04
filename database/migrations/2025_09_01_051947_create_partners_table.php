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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('workflow_id');
            $table->foreignId('master_cat_id')->constrained('master_categories')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('partner_type_id')->constrained('partner_type_setups')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('state_id')->constrained('states')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->nullable()
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('brn')->nullable();
            $table->string('currency')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('photo')->nullable();
            $table->string('partner_branch_id')->nullable();
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
        Schema::dropIfExists('partners');
    }
};
