<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // laravel schema builder doesn't directly support mediumblob
        DB::statement("ALTER TABLE reports ADD document MEDIUMBLOB");
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
            $table->dropColumn('document');
        });
        Schema::enableForeignKeyConstraints();
    }
}
