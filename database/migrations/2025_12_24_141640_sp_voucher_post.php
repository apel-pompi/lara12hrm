<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
        CREATE PROCEDURE sp_am_voucherpost(
            IN v_voucherno VARCHAR(50),
            IN pUser INT
        )
        BEGIN
           
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                
                RESIGNAL;
            END;

            
            INSERT INTO voucher_balances(
                vouchernumber, accountcode, subacccode, voucherdate,
                branch_id, referance, yearname, monthname,
                currency, exchagerate, primeamt, baseamt,
                status, user_id, created_at, updated_at
            )
            SELECT 
                a.vouchernumber, b.accountcode, b.subacccode, a.voucherdate,
                a.branch_id, a.referance, a.yearname, a.monthname,
                b.currency, b.exchagerate, b.primeamt, b.baseamt,
                'Post', pUser, NOW(), NOW()
            FROM voucherheaders a
            INNER JOIN voucherdetails b
                ON a.vouchernumber = b.vouchernumber
            WHERE a.status = 'Balanced'
              AND a.vouchernumber = v_voucherno
              AND NOT EXISTS (
                  SELECT 1 
                  FROM voucher_balances vb 
                  WHERE vb.vouchernumber = a.vouchernumber 
                    AND vb.accountcode = b.accountcode
                    AND vb.subacccode = b.subacccode
              );

            
            UPDATE voucherheaders
            SET status = 'Posted',
                updated_at = NOW(),
                user_id = pUser
            WHERE vouchernumber = v_voucherno
              AND status = 'Balanced';
        END
    ");
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
