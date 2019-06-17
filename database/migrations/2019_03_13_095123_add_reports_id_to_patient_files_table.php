<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportsIdToPatientFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_files', function (Blueprint $table) {
            $table->integer('reports_id')->nullable();
            $table->foreign('reports_id')->references('reports_id')->on('reports');
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
        Schema::table('patient_files', function (Blueprint $table) {
            $table->dropForeign('patient_files_reports_id_foreign');
            $table->dropColumn('reports_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
