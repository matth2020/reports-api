<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UserGroup;

class CreateUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_group');
        Schema::create('user_group', function (Blueprint $table) {
            $table->integer('user_group_id')->autoIncrement();
            $table->string('name', 32)->unique();
        });
        $defaultGroups = [
            ['name' => 'Xtract Admin', 'user_group_id' => 1],
            ['name' => 'Xtract Support', 'user_group_id' => 2],
            ['name' => 'Admin', 'user_group_id' => 3],
            ['name' => 'Provider', 'user_group_id' => 4],
            ['name' => 'Provider limited', 'user_group_id' => 5],
            ['name' => 'Injection Super', 'user_group_id' => 6],
            ['name' => 'Injection User', 'user_group_id' => 7],
            ['name' => 'Injection Limited', 'user_group_id' => 8],
            ['name' => 'Pharmacy User', 'user_group_id' => 9],
            ['name' => 'Mix Tech', 'user_group_id' => 10],
            ['name' => 'Mix Limited', 'user_group_id' => 11]
        ];
        UserGroup::insert($defaultGroups);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_group');
        Schema::enableForeignKeyConstraints();
    }
}
