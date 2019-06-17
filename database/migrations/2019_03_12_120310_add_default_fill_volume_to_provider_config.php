<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultFillVolumeToProviderConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //ALTER TABLE `provider_config` ADD COLUMN `defaultFillVolume` VARCHAR(45) NULL AFTER `doseRules`;
        Schema::table('provider_config', function (Blueprint $table) {
            $table->string('default_fill_volume', 45)->nullable()->after('doseRules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_config', function (Blueprint $table) {
            $table->dropColumn('default_fill_volume');
        });
    }
}
