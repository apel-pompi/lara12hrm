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
        Schema::table('student_invoice_hd', function (Blueprint $table) {
            $table->text('shortnote')
                  ->nullable()
                  ->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_invoice_hd', function (Blueprint $table) {
            $table->dropColumn('shortnote');
        });
    }
};
