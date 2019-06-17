<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountStatusIdToAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account', function (Blueprint $table) {
            $table->integer('account_status_id')->nullable();
            $table->foreign('account_status_id')->references('account_status_id')->on('account_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('account', function (Blueprint $table) {
            $table->dropForeign('account_account_status_id_foreign');
            $table->dropColumn('account_status_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
