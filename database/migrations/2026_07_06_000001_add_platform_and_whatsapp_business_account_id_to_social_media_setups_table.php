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
        Schema::table('social_media_setups', function (Blueprint $table) {
            if (!Schema::hasColumn('social_media_setups', 'platform')) {
                $table->string('platform')->default('facebook')->after('id');
            }

            if (!Schema::hasColumn('social_media_setups', 'whatsapp_business_account_id')) {
                $table->string('whatsapp_business_account_id')->nullable()->after('page_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_media_setups', function (Blueprint $table) {
            if (Schema::hasColumn('social_media_setups', 'whatsapp_business_account_id')) {
                $table->dropColumn('whatsapp_business_account_id');
            }

            if (Schema::hasColumn('social_media_setups', 'platform')) {
                $table->dropColumn('platform');
            }
        });
    }
};
