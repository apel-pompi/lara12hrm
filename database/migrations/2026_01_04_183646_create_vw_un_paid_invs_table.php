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
        CREATE VIEW vw_unpaidinv AS
        SELECT
            a.subacccode                         AS suppliercode,
            b.name                               AS suppliername,
            e.branchname                         AS branch,
            a.accountcode,
            d.description,
            b.contact_person,
            ABS(SUM(a.primeamt))                 AS payable
        FROM voucherdetails a
        INNER JOIN suppliers b
            ON a.subacccode = b.subcode
        INNER JOIN voucherheaders c
            ON c.vouchernumber = a.vouchernumber
        INNER JOIN branches e
            ON e.id = c.branch_id
        INNER JOIN chart_of_accounts d
            ON d.accountcode = a.accountcode
        WHERE c.status = 'Posted'
        GROUP BY
            a.subacccode,
            b.name,
            e.branchname,
            a.accountcode,
            d.description,
            b.contact_person,
            c.branch_id;
        SQL;
    }
};
