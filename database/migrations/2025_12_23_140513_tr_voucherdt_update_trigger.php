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
        DB::unprepared("
            CREATE TRIGGER `tr_voucherdt_update` 
            AFTER UPDATE ON `voucherdetails`
            FOR EACH ROW
            BEGIN
                DECLARE total DECIMAL(20,2);

                -- Calculate total base amount for the voucher
                SELECT SUM(baseamt) INTO total
                FROM voucherdetails
                WHERE vouchernumber = NEW.vouchernumber;

                -- Update voucherheaders status
                IF total = 0 THEN
                    UPDATE voucherheaders
                    SET status = 'Balanced'
                    WHERE vouchernumber = NEW.vouchernumber;
                ELSE
                    UPDATE voucherheaders
                    SET status = 'Suspend'
                    WHERE vouchernumber = NEW.vouchernumber;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER `tr_voucherdt_update`");
    }
};
