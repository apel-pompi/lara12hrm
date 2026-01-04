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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('subcode')->nullable();
            $table->string('name')->nullable();
            $table->string('subaddress')->nullable();
            $table->string('subcountry')->nullable();
            $table->string('substate')->nullable();
            $table->string('subcity')->nullable();
            $table->string('subzipcode')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('subphone')->nullable();
            $table->string('subemail')->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('active');
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
        Schema::dropIfExists('suppliers');
    }
};
