<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->integer('template_id')->nullable();
            $table->foreign('template_id')->references('template_id')->on('template');
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
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('reports_template_id_foreign');
            $table->dropColumn('template_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
