<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGen2LogView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("drop view if exists `gen2_log`");
        DB::statement("create view gen2_log as
            select xis_log.xis_log_id, '' as api_log_id, xis_log.timestamp, user.user_id, xis_log.username, xis_log.event, xis_log.compname as comp, xis_log.patient_id, xis_log.prescription_id, xis_log.error, '' as response_code from xis_log left join user on user.displayname = xis_log.username union all select '' as xis_log_id, api_log.api_log_id, api_log.timestamp, api_log.user_id, user.displayname as username, concat(api_log.method,' - ',api_log.path) as event, api_log.requester_ip as comp, '' as patient_id, '' as prescription_id, if(api_log.response_code = 200, '', api_log.response_code) as error, api_log.response_code from api_log left join user on user.user_id=api_log.user_id order by timestamp asc;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view if exists `gen2_log`");
    }
}
