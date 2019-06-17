<?php

use App\Models\User;
use App\Models\UserGroupUser;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_group_user');
        Schema::create('user_group_user', function (Blueprint $table) {
            $table->integer('user_group_id');
            $table->integer('user_id');
            $table->foreign('user_group_id')->references('user_group_id')->on('user_group');
            $table->foreign('user_id')->references('user_id')->on('user');
            $table->primary(['user_id','user_group_id']);
        });
        // auto assign xtract admin if they are in the db
        $XtractAdmin = User::where('displayname', 'Xtract Admin')->first();
        if (!is_null($XtractAdmin)) {
            UserGroupUser::insert(['user_id' => $XtractAdmin->user_id, 'user_group_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_group_user');
        Schema::enableForeignKeyConstraints();
    }
}
