<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        DB::statement('DROP VIEW IF EXISTS vw_apayable');
    }

    private function viewSql(): string
    {
        return <<<SQL
        CREATE VIEW vw_apayable AS
        SELECT
            a.subacccode                         AS suppliercode,
            b.name                               AS suppliername,
            c.branch_id                          AS branch_id,
            a.accountcode,
            d.description,
            b.contact_person,
            ABS(SUM(a.primeamt))                 AS payableamt
        FROM voucherdetails a
        INNER JOIN suppliers b
            ON a.subacccode = b.subcode
        INNER JOIN voucherheaders c
            ON c.vouchernumber = a.vouchernumber
        INNER JOIN chart_of_accounts d
            ON d.accountcode = a.accountcode
        GROUP BY
            a.subacccode,
            b.name,
            a.accountcode,
            d.description,
            b.contact_person,
            c.branch_id;
        SQL;
    }
};
