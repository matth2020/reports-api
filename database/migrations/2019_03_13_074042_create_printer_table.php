<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Printer;

class CreatePrinterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('printer');
        Schema::create('printer', function (Blueprint $table) {
            $table->integer('printer_id')->autoIncrement();
            $table->string('external_id', 64)->nullable()->unique();
            $table->string('name', 64);
            $table->string('type', 32);
            $table->softDeletes();
        });

        // emr printer
        $Emr = new Printer();
        $Emr->name = 'Print to EMR';
        $Emr->type = 'EMR';
        $Emr->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('printer');
        Schema::enableForeignKeyConstraints();
    }
}
