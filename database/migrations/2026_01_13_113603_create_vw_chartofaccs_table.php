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

            b.groupone AS groupone_code,
            b.description AS groupone_name,

            c.grouptwo AS grouptwo_code,
            c.description AS grouptwo_name,

            d.groupthree AS groupthree_code,
            d.description AS groupthree_name

        FROM chart_of_accounts a
        LEFT JOIN group_ones b 
            ON a.groupone = b.groupone
        LEFT JOIN group_twos c 
            ON a.groupone = c.groupone 
            AND a.grouptwo = c.grouptwo
        LEFT JOIN group_threes d 
            ON a.groupone = d.groupone 
            AND a.grouptwo = d.grouptwo 
            AND a.groupthree = d.groupthree;
        SQL;
    }
};

