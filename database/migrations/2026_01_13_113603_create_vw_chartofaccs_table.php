<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement($this->viewSql());
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_chartofaccs');
    }

    private function viewSql(): string
    {
        return <<<SQL
        CREATE VIEW vw_chartofaccs AS
        SELECT 
            a.id,
            a.accountcode,
            a.description AS ledger_name,
            a.accounttype,
            a.accountusage,
            a.analyticalcode,
            a.active,

            g1.code        AS groupone_code,
            g1.description AS groupone_name,

            g2.code        AS grouptwo_code,
            g2.description AS grouptwo_name,

            g3.code        AS groupthree_code,
            g3.description AS groupthree_name

        FROM chart_of_accounts a
        LEFT JOIN group_ones g1 
            ON a.groupone = g1.id

        LEFT JOIN group_twos g2 
            ON a.grouptwo = g2.id

        LEFT JOIN group_threes g3 
            ON a.groupthree = g3.id;
        SQL;
    }
};

