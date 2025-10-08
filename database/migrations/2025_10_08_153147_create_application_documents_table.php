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
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->nullable();
            $table->foreignId('applcation_id')->constrained('student_applications')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('workflow_id')->constrained('workflows')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained('partners')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('workflow_stages')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('doc_id')->constrained('w_document_types')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('docname')->nullable();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
