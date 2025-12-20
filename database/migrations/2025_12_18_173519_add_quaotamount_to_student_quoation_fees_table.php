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
        Schema::table('student_quoation_fees', function (Blueprint $table) {
            $table->decimal('quaotamount', 20, 2)
                  ->nullable()
                  ->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_quoation_fees', function (Blueprint $table) {
            $table->dropColumn('quaotamount');
        });
    }
};
