<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TemplatePrinter;

class CreateTemplatePrinterTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('template_printer');
        Schema::create('template_printer', function (Blueprint $table) {
            $table->integer('template_id');
            $table->integer('printer_id');
            $table->primary(['template_id', 'printer_id']);
            $table->foreign('printer_id')->references('printer_id')->on('printer');
            $table->foreign('template_id')->references('template_id')->on('template');
        });

        // makes the 4 default reports emr capable
        TemplatePrinter::insert([
            ['template_id' => 1, 'printer_id' => 1],
            ['template_id' => 2, 'printer_id' => 1],
            ['template_id' => 3, 'printer_id' => 1],
            ['template_id' => 4, 'printer_id' => 1]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('template_printer');
        Schema::enableForeignKeyConstraints();
    }
}
