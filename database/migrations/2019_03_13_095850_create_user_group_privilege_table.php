<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UserGroupPrivilege;

class CreateUserGroupPrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_group_privilege');
        Schema::create('user_group_privilege', function (Blueprint $table) {
            $table->integer('user_group_id');
            $table->integer('privilege_id');
            $table->foreign('user_group_id')->references('user_group_id')->on('user_group');
            $table->foreign('privilege_id')->references('privilege_id')->on('privilege');
            $table->primary(['user_group_id','privilege_id']);
        });
        $defaultUserGroupPrivileges = [
            // xtract admin all priv group
            ['user_group_id' => 1, 'privilege_id' => 1],
            ['user_group_id' => 1, 'privilege_id' => 2],
            ['user_group_id' => 1, 'privilege_id' => 3],
            ['user_group_id' => 1, 'privilege_id' => 4],
            ['user_group_id' => 1, 'privilege_id' => 5],
            ['user_group_id' => 1, 'privilege_id' => 6],
            ['user_group_id' => 1, 'privilege_id' => 7],
            ['user_group_id' => 1, 'privilege_id' => 8],
            ['user_group_id' => 1, 'privilege_id' => 9],
            ['user_group_id' => 1, 'privilege_id' => 10],
            ['user_group_id' => 1, 'privilege_id' => 11],
            ['user_group_id' => 1, 'privilege_id' => 12],
            ['user_group_id' => 1, 'privilege_id' => 13],
            ['user_group_id' => 1, 'privilege_id' => 14],
            ['user_group_id' => 1, 'privilege_id' => 15],
            ['user_group_id' => 1, 'privilege_id' => 16],
            ['user_group_id' => 1, 'privilege_id' => 17],
            ['user_group_id' => 1, 'privilege_id' => 18],
            ['user_group_id' => 1, 'privilege_id' => 19],
            ['user_group_id' => 1, 'privilege_id' => 20],
            ['user_group_id' => 1, 'privilege_id' => 21],
            ['user_group_id' => 1, 'privilege_id' => 22],
            ['user_group_id' => 1, 'privilege_id' => 23],
            ['user_group_id' => 1, 'privilege_id' => 24],
            ['user_group_id' => 1, 'privilege_id' => 25],
            ['user_group_id' => 1, 'privilege_id' => 26],
            ['user_group_id' => 1, 'privilege_id' => 27],
            ['user_group_id' => 1, 'privilege_id' => 28],
            ['user_group_id' => 1, 'privilege_id' => 29],
            ['user_group_id' => 1, 'privilege_id' => 30],
            ['user_group_id' => 1, 'privilege_id' => 31],
            ['user_group_id' => 1, 'privilege_id' => 32],
            ['user_group_id' => 1, 'privilege_id' => 33],
            ['user_group_id' => 1, 'privilege_id' => 34],
            ['user_group_id' => 1, 'privilege_id' => 35],
            ['user_group_id' => 1, 'privilege_id' => 36],
            ['user_group_id' => 1, 'privilege_id' => 37],
            ['user_group_id' => 1, 'privilege_id' => 38],
            ['user_group_id' => 1, 'privilege_id' => 39],
            ['user_group_id' => 1, 'privilege_id' => 40],
            ['user_group_id' => 1, 'privilege_id' => 41],
            ['user_group_id' => 1, 'privilege_id' => 42],
            ['user_group_id' => 1, 'privilege_id' => 43],
            ['user_group_id' => 1, 'privilege_id' => 44],
            ['user_group_id' => 1, 'privilege_id' => 45],
            ['user_group_id' => 1, 'privilege_id' => 46],
            ['user_group_id' => 1, 'privilege_id' => 47],
            ['user_group_id' => 1, 'privilege_id' => 48],
            ['user_group_id' => 1, 'privilege_id' => 49],
            // xtract support group
            ['user_group_id' => 2, 'privilege_id' => 1],
            ['user_group_id' => 2, 'privilege_id' => 3],
            ['user_group_id' => 2, 'privilege_id' => 4],
            ['user_group_id' => 2, 'privilege_id' => 6],
            ['user_group_id' => 2, 'privilege_id' => 7],
            ['user_group_id' => 2, 'privilege_id' => 8],
            ['user_group_id' => 2, 'privilege_id' => 9],
            ['user_group_id' => 2, 'privilege_id' => 10],
            ['user_group_id' => 2, 'privilege_id' => 11],
            ['user_group_id' => 2, 'privilege_id' => 13],
            ['user_group_id' => 2, 'privilege_id' => 14],
            ['user_group_id' => 2, 'privilege_id' => 16],
            ['user_group_id' => 2, 'privilege_id' => 17],
            ['user_group_id' => 2, 'privilege_id' => 18],
            ['user_group_id' => 2, 'privilege_id' => 19],
            ['user_group_id' => 2, 'privilege_id' => 20],
            ['user_group_id' => 2, 'privilege_id' => 21],
            ['user_group_id' => 2, 'privilege_id' => 22],
            ['user_group_id' => 2, 'privilege_id' => 23],
            ['user_group_id' => 2, 'privilege_id' => 25],
            ['user_group_id' => 2, 'privilege_id' => 26],
            ['user_group_id' => 2, 'privilege_id' => 27],
            ['user_group_id' => 2, 'privilege_id' => 29],
            ['user_group_id' => 2, 'privilege_id' => 30],
            ['user_group_id' => 2, 'privilege_id' => 31],
            ['user_group_id' => 2, 'privilege_id' => 32],
            ['user_group_id' => 2, 'privilege_id' => 33],
            ['user_group_id' => 2, 'privilege_id' => 34],
            ['user_group_id' => 2, 'privilege_id' => 35],
            ['user_group_id' => 2, 'privilege_id' => 36],
            ['user_group_id' => 2, 'privilege_id' => 37],
            ['user_group_id' => 2, 'privilege_id' => 38],
            ['user_group_id' => 2, 'privilege_id' => 39],
            ['user_group_id' => 2, 'privilege_id' => 40],
            ['user_group_id' => 2, 'privilege_id' => 48],
            // admin all non-xtract priv group
            ['user_group_id' => 3, 'privilege_id' => 1],
            ['user_group_id' => 3, 'privilege_id' => 2],
            ['user_group_id' => 3, 'privilege_id' => 3],
            ['user_group_id' => 3, 'privilege_id' => 4],
            ['user_group_id' => 3, 'privilege_id' => 5],
            ['user_group_id' => 3, 'privilege_id' => 6],
            ['user_group_id' => 3, 'privilege_id' => 7],
            ['user_group_id' => 3, 'privilege_id' => 8],
            ['user_group_id' => 3, 'privilege_id' => 9],
            ['user_group_id' => 3, 'privilege_id' => 10],
            ['user_group_id' => 3, 'privilege_id' => 11],
            ['user_group_id' => 3, 'privilege_id' => 12],
            ['user_group_id' => 3, 'privilege_id' => 13],
            ['user_group_id' => 3, 'privilege_id' => 14],
            ['user_group_id' => 3, 'privilege_id' => 15],
            ['user_group_id' => 3, 'privilege_id' => 16],
            ['user_group_id' => 3, 'privilege_id' => 17],
            ['user_group_id' => 3, 'privilege_id' => 18],
            ['user_group_id' => 3, 'privilege_id' => 19],
            ['user_group_id' => 3, 'privilege_id' => 20],
            ['user_group_id' => 3, 'privilege_id' => 21],
            ['user_group_id' => 3, 'privilege_id' => 22],
            ['user_group_id' => 3, 'privilege_id' => 23],
            ['user_group_id' => 3, 'privilege_id' => 24],
            ['user_group_id' => 3, 'privilege_id' => 25],
            ['user_group_id' => 3, 'privilege_id' => 26],
            ['user_group_id' => 3, 'privilege_id' => 27],
            ['user_group_id' => 3, 'privilege_id' => 28],
            ['user_group_id' => 3, 'privilege_id' => 29],
            ['user_group_id' => 3, 'privilege_id' => 30],
            ['user_group_id' => 3, 'privilege_id' => 31],
            ['user_group_id' => 3, 'privilege_id' => 32],
            ['user_group_id' => 3, 'privilege_id' => 33],
            ['user_group_id' => 3, 'privilege_id' => 34],
            ['user_group_id' => 3, 'privilege_id' => 35],
            ['user_group_id' => 3, 'privilege_id' => 36],
            ['user_group_id' => 3, 'privilege_id' => 37],
            ['user_group_id' => 3, 'privilege_id' => 38],
            ['user_group_id' => 3, 'privilege_id' => 39],
            ['user_group_id' => 3, 'privilege_id' => 40],
            ['user_group_id' => 3, 'privilege_id' => 41],
            ['user_group_id' => 3, 'privilege_id' => 42],
            ['user_group_id' => 3, 'privilege_id' => 43],
            ['user_group_id' => 3, 'privilege_id' => 44],
            ['user_group_id' => 3, 'privilege_id' => 45],
            ['user_group_id' => 3, 'privilege_id' => 46],
            ['user_group_id' => 3, 'privilege_id' => 47],
            // Provider priv group
            ['user_group_id' => 4, 'privilege_id' => 1],
            ['user_group_id' => 4, 'privilege_id' => 2],
            ['user_group_id' => 4, 'privilege_id' => 3],
            ['user_group_id' => 4, 'privilege_id' => 4],
            ['user_group_id' => 4, 'privilege_id' => 5],
            ['user_group_id' => 4, 'privilege_id' => 6],
            ['user_group_id' => 4, 'privilege_id' => 7],
            ['user_group_id' => 4, 'privilege_id' => 8],
            ['user_group_id' => 4, 'privilege_id' => 9],
            ['user_group_id' => 4, 'privilege_id' => 10],
            ['user_group_id' => 4, 'privilege_id' => 11],
            ['user_group_id' => 4, 'privilege_id' => 12],
            ['user_group_id' => 4, 'privilege_id' => 13],
            ['user_group_id' => 4, 'privilege_id' => 14],
            ['user_group_id' => 4, 'privilege_id' => 15],
            ['user_group_id' => 4, 'privilege_id' => 16],
            ['user_group_id' => 4, 'privilege_id' => 17],
            ['user_group_id' => 4, 'privilege_id' => 18],
            ['user_group_id' => 4, 'privilege_id' => 19],
            ['user_group_id' => 4, 'privilege_id' => 20],
            ['user_group_id' => 4, 'privilege_id' => 21],
            ['user_group_id' => 4, 'privilege_id' => 22],
            ['user_group_id' => 4, 'privilege_id' => 23],
            ['user_group_id' => 4, 'privilege_id' => 27],
            ['user_group_id' => 4, 'privilege_id' => 28],
            ['user_group_id' => 4, 'privilege_id' => 29],
            ['user_group_id' => 4, 'privilege_id' => 30],
            ['user_group_id' => 4, 'privilege_id' => 31],
            ['user_group_id' => 4, 'privilege_id' => 32],
            ['user_group_id' => 4, 'privilege_id' => 33],
            ['user_group_id' => 4, 'privilege_id' => 34],
            ['user_group_id' => 4, 'privilege_id' => 35],
            ['user_group_id' => 4, 'privilege_id' => 36],
            ['user_group_id' => 4, 'privilege_id' => 37],
            ['user_group_id' => 4, 'privilege_id' => 38],
            ['user_group_id' => 4, 'privilege_id' => 39],
            ['user_group_id' => 4, 'privilege_id' => 40],
            // Provider Limited priv group
            ['user_group_id' => 5, 'privilege_id' => 1],
            ['user_group_id' => 5, 'privilege_id' => 2],
            ['user_group_id' => 5, 'privilege_id' => 3],
            ['user_group_id' => 5, 'privilege_id' => 4],
            ['user_group_id' => 5, 'privilege_id' => 5],
            ['user_group_id' => 5, 'privilege_id' => 6],
            ['user_group_id' => 5, 'privilege_id' => 7],
            ['user_group_id' => 5, 'privilege_id' => 8],
            ['user_group_id' => 5, 'privilege_id' => 9],
            ['user_group_id' => 5, 'privilege_id' => 10],
            ['user_group_id' => 5, 'privilege_id' => 11],
            ['user_group_id' => 5, 'privilege_id' => 12],
            ['user_group_id' => 5, 'privilege_id' => 13],
            ['user_group_id' => 5, 'privilege_id' => 14],
            ['user_group_id' => 5, 'privilege_id' => 15],
            ['user_group_id' => 5, 'privilege_id' => 16],
            ['user_group_id' => 5, 'privilege_id' => 17],
            ['user_group_id' => 5, 'privilege_id' => 18],
            ['user_group_id' => 5, 'privilege_id' => 19],
            ['user_group_id' => 5, 'privilege_id' => 20],
            ['user_group_id' => 5, 'privilege_id' => 27],
            ['user_group_id' => 5, 'privilege_id' => 28],
            ['user_group_id' => 5, 'privilege_id' => 29],
            ['user_group_id' => 5, 'privilege_id' => 30],
            // Injection Super priv group
            ['user_group_id' => 6, 'privilege_id' => 1],
            ['user_group_id' => 6, 'privilege_id' => 2],
            ['user_group_id' => 6, 'privilege_id' => 3],
            ['user_group_id' => 6, 'privilege_id' => 4],
            ['user_group_id' => 6, 'privilege_id' => 5],
            ['user_group_id' => 6, 'privilege_id' => 6],
            ['user_group_id' => 6, 'privilege_id' => 7],
            ['user_group_id' => 6, 'privilege_id' => 8],
            ['user_group_id' => 6, 'privilege_id' => 9],
            ['user_group_id' => 6, 'privilege_id' => 10],
            ['user_group_id' => 6, 'privilege_id' => 11],
            ['user_group_id' => 6, 'privilege_id' => 12],
            ['user_group_id' => 6, 'privilege_id' => 13],
            ['user_group_id' => 6, 'privilege_id' => 14],
            ['user_group_id' => 6, 'privilege_id' => 15],
            ['user_group_id' => 6, 'privilege_id' => 16],
            ['user_group_id' => 6, 'privilege_id' => 17],
            ['user_group_id' => 6, 'privilege_id' => 18],
            ['user_group_id' => 6, 'privilege_id' => 19],
            ['user_group_id' => 6, 'privilege_id' => 20],
            ['user_group_id' => 6, 'privilege_id' => 21],
            ['user_group_id' => 6, 'privilege_id' => 22],
            ['user_group_id' => 6, 'privilege_id' => 23],
            ['user_group_id' => 6, 'privilege_id' => 27],
            ['user_group_id' => 6, 'privilege_id' => 28],
            ['user_group_id' => 6, 'privilege_id' => 29],
            ['user_group_id' => 6, 'privilege_id' => 30],
            ['user_group_id' => 6, 'privilege_id' => 31],
            ['user_group_id' => 6, 'privilege_id' => 32],
            ['user_group_id' => 6, 'privilege_id' => 33],
            // Injection User priv group
            ['user_group_id' => 7, 'privilege_id' => 1],
            ['user_group_id' => 7, 'privilege_id' => 2],
            ['user_group_id' => 7, 'privilege_id' => 3],
            ['user_group_id' => 7, 'privilege_id' => 5],
            ['user_group_id' => 7, 'privilege_id' => 6],
            ['user_group_id' => 7, 'privilege_id' => 7],
            ['user_group_id' => 7, 'privilege_id' => 8],
            ['user_group_id' => 7, 'privilege_id' => 11],
            ['user_group_id' => 7, 'privilege_id' => 12],
            ['user_group_id' => 7, 'privilege_id' => 13],
            ['user_group_id' => 7, 'privilege_id' => 14],
            ['user_group_id' => 7, 'privilege_id' => 15],
            ['user_group_id' => 7, 'privilege_id' => 16],
            ['user_group_id' => 7, 'privilege_id' => 17],
            ['user_group_id' => 7, 'privilege_id' => 18],
            ['user_group_id' => 7, 'privilege_id' => 19],
            ['user_group_id' => 7, 'privilege_id' => 20],
            ['user_group_id' => 7, 'privilege_id' => 27],
            ['user_group_id' => 7, 'privilege_id' => 28],
            ['user_group_id' => 7, 'privilege_id' => 29],
            ['user_group_id' => 7, 'privilege_id' => 30],
            // Injection Limited priv group
            ['user_group_id' => 8, 'privilege_id' => 1],
            ['user_group_id' => 8, 'privilege_id' => 2],
            ['user_group_id' => 8, 'privilege_id' => 3],
            ['user_group_id' => 8, 'privilege_id' => 5],
            ['user_group_id' => 8, 'privilege_id' => 11],
            ['user_group_id' => 8, 'privilege_id' => 12],
            ['user_group_id' => 8, 'privilege_id' => 13],
            ['user_group_id' => 8, 'privilege_id' => 14],
            ['user_group_id' => 8, 'privilege_id' => 20],
            ['user_group_id' => 8, 'privilege_id' => 27],
            ['user_group_id' => 8, 'privilege_id' => 28],
            ['user_group_id' => 8, 'privilege_id' => 29],
            ['user_group_id' => 8, 'privilege_id' => 30],
            // Pharmacy User priv group
            ['user_group_id' => 9, 'privilege_id' => 5],
            ['user_group_id' => 9, 'privilege_id' => 6],
            ['user_group_id' => 9, 'privilege_id' => 7],
            ['user_group_id' => 9, 'privilege_id' => 8],
            ['user_group_id' => 9, 'privilege_id' => 9],
            ['user_group_id' => 9, 'privilege_id' => 10],
            ['user_group_id' => 9, 'privilege_id' => 11],
            ['user_group_id' => 9, 'privilege_id' => 20],
            ['user_group_id' => 9, 'privilege_id' => 27],
            ['user_group_id' => 9, 'privilege_id' => 28],
            ['user_group_id' => 9, 'privilege_id' => 29],
            ['user_group_id' => 9, 'privilege_id' => 30],
            ['user_group_id' => 9, 'privilege_id' => 31],
            ['user_group_id' => 9, 'privilege_id' => 32],
            ['user_group_id' => 9, 'privilege_id' => 33],
            ['user_group_id' => 9, 'privilege_id' => 34],
            ['user_group_id' => 9, 'privilege_id' => 35],
            ['user_group_id' => 9, 'privilege_id' => 36],
            ['user_group_id' => 9, 'privilege_id' => 37],
            ['user_group_id' => 9, 'privilege_id' => 38],
            ['user_group_id' => 9, 'privilege_id' => 39],
            ['user_group_id' => 9, 'privilege_id' => 40],
            // Mixing tech priv group
            ['user_group_id' => 10, 'privilege_id' => 5],
            ['user_group_id' => 10, 'privilege_id' => 8],
            ['user_group_id' => 10, 'privilege_id' => 9],
            ['user_group_id' => 10, 'privilege_id' => 27],
            ['user_group_id' => 10, 'privilege_id' => 34],
            ['user_group_id' => 10, 'privilege_id' => 35],
            ['user_group_id' => 10, 'privilege_id' => 36],
            // Mixing limited priv group
            ['user_group_id' => 11, 'privilege_id' => 5],
            ['user_group_id' => 11, 'privilege_id' => 9],
            ['user_group_id' => 11, 'privilege_id' => 27]
        ];
        UserGroupPrivilege::insert($defaultUserGroupPrivileges);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_group_privilege');
        Schema::enableForeignKeyConstraints();
    }
}
