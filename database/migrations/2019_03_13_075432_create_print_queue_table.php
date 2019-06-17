<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('print_queue');
        Schema::create('print_queue', function (Blueprint $table) {
            $table->integer('print_queue_id')->autoIncrement();
            $table->integer('printer_id');
            $table->integer('copies')->default(1);
            $table->integer('reports_id');
            $table->timestamp('processed_at')->nullable()->default(\DB::raw('null on update CURRENT_TIMESTAMP'));
            $table->string('status', 32)->nullable()->default('submitted');
            $table->string('auth_id', 255)->nullable();
            $table->string('auth_key', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by');
            $table->foreign('reports_id')->references('reports_id')->on('reports');
            $table->foreign('printer_id')->references('printer_id')->on('printer');
            $table->foreign('created_by')->references('user_id')->on('user');
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
        Schema::dropIfExists('print_queue');
        Schema::enableForeignKeyConstraints();
    }
}
