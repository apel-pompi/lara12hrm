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
        Schema::create('w_document_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('doctype_id')->constrained('w_document_types')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('workstage_id')->constrained('workflow_stages')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('active');
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
        Schema::dropIfExists('w_document_checks');
    }
};
