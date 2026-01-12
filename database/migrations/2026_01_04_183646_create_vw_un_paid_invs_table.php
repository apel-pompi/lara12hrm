<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement($this->viewSql());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_unpaidinv');
    }

    /**
     * SQL for unpaid supplier invoice view
     */
    private function viewSql(): string
    {
        return <<<SQL
        CREATE OR REPLACE VIEW vw_unpaidinv AS
        SELECT
            vw_payinvc.suppliercode       AS suppliercode,
            vw_payinvc.invicenumber       AS invoicenumber,
            vw_payinvc.date               AS date,
            vw_payinvc.branch_id          AS branch_id,
            vw_payinvc.currency           AS currency,
            vw_payinvc.exchagerate        AS exchagerate,
            ABS(SUM(vw_payinvc.amount))   AS primeamt,
            ABS(SUM(vw_payinvc.primeamt)) AS amount
        FROM vw_payinvc
        GROUP BY
            vw_payinvc.suppliercode,
            vw_payinvc.invicenumber,
            vw_payinvc.date,
            vw_payinvc.branch_id,
            vw_payinvc.currency,
            vw_payinvc.exchagerate
        HAVING
            ABS(SUM(vw_payinvc.primeamt)) > 0;
        SQL;
    }
};
