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
        DB::statement('DROP VIEW IF EXISTS vw_payinvc');
    }

    private function viewSql(): string
    {
        return <<<SQL
        CREATE VIEW vw_payinvc AS

        /* Supplier Invoice (AP--) */
        SELECT
            a.subacccode                    AS suppliercode,
            a.vouchernumber                 AS invicenumber,
            c.voucherdate                   AS date,
            h.branchname                    AS branch,
            a.currency,
            a.exchagerate,
            a.primeamt,
            a.baseamt                       AS amount
        FROM voucherdetails a
        INNER JOIN suppliers b
            ON a.subacccode = b.subcode
        INNER JOIN voucherheaders c
            ON c.vouchernumber = a.vouchernumber
        INNER JOIN branches h
            ON h.id = c.branch_id
        WHERE LEFT(a.vouchernumber, 4) = 'AP--'

        UNION ALL

        /* Supplier Payments */
        SELECT
            e.subacccode                    AS suppliercode,
            d.invnumber                     AS invicenumber,
            f.voucherdate                   AS date,
            h.branchname                    AS branch,
            d.currency,
            d.exchagerate,
            d.primeamt,
            d.baseamt                       AS amount
        FROM apalcs d
        INNER JOIN voucherdetails e
            ON d.vouchernumber = e.vouchernumber
        INNER JOIN voucherheaders f
            ON f.vouchernumber = e.vouchernumber
        INNER JOIN branches h
            ON h.id = f.branch_id
        INNER JOIN suppliers g
            ON e.subacccode = g.subcode;
        SQL;
    }
};
