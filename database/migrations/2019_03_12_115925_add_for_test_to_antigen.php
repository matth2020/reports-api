<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForTestToAntigen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //ALTER TABLE `antigen` ADD COLUMN `for_test_only` VARCHAR(45) NULL DEFAULT 'F' AFTER `test_order`;
        Schema::table('antigen', function (Blueprint $table) {
            $table->string('for_test_only', 45)->nullable()->default('F')->after('test_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('antigen', function (Blueprint $table) {
            $table->dropColumn('for_test_only');
        });
    }
}
