<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Privilege;

class CreatePrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('privilege');
        Schema::create('privilege', function (Blueprint $table) {
            $table->integer('privilege_id')->autoIncrement();
            $table->string('name', 32)->unique();
        });
        $defaultScopes = [
            ['name' => 'injection_read', 'privilege_id' => 1],
            ['name' => 'injection_create', 'privilege_id' => 2],
            ['name' => 'injection_update', 'privilege_id' => 3],
            ['name' => 'injection_delete', 'privilege_id' => 4],
            ['name' => 'prescription_read', 'privilege_id' => 5],
            ['name' => 'prescription_create', 'privilege_id' => 6],
            ['name' => 'prescription_update', 'privilege_id' => 7],
            ['name' => 'prescription_mix_update', 'privilege_id' => 8],
            ['name' => 'prescription_status_update', 'privilege_id' => 9],
            ['name' => 'prescription_delete', 'privilege_id' => 10],
            ['name' => 'skintest_read', 'privilege_id' => 11],
            ['name' => 'skintest_create', 'privilege_id' => 12],
            ['name' => 'skintest_update', 'privilege_id' => 13],
            ['name' => 'skintest_delete', 'privilege_id' => 14],
            ['name' => 'injection_adjust_create', 'privilege_id' => 15],
            ['name' => 'injection_adjust_update', 'privilege_id' => 16],
            ['name' => 'injection_adjust_delete', 'privilege_id' => 17],
            ['name' => 'questionnaire_lockout_create', 'privilege_id' => 18],
            ['name' => 'questionnaire_lockout_delete', 'privilege_id' => 19],
            ['name' => 'activate_vials_update', 'privilege_id' => 20],
            ['name' => 'treatment_plan_create', 'privilege_id' => 21],
            ['name' => 'treatment_plan_update', 'privilege_id' => 22],
            ['name' => 'treatment_plan_delete', 'privilege_id' => 23],
            ['name' => 'dosing_plan_create', 'privilege_id' => 24],
            ['name' => 'dosing_plan_update', 'privilege_id' => 25],
            ['name' => 'dosing_plan_delete', 'privilege_id' => 26],
            ['name' => 'patient_read', 'privilege_id' => 27],
            ['name' => 'patient_create', 'privilege_id' => 28],
            ['name' => 'patient_update', 'privilege_id' => 29],
            ['name' => 'patient_delete', 'privilege_id' => 30],
            ['name' => 'provider_create', 'privilege_id' => 31],
            ['name' => 'provider_update', 'privilege_id' => 32],
            ['name' => 'provider_delete', 'privilege_id' => 33],
            ['name' => 'inventory_create', 'privilege_id' => 34],
            ['name' => 'inventory_update', 'privilege_id' => 35],
            ['name' => 'inventory_delete', 'privilege_id' => 36],
            ['name' => 'extract_create', 'privilege_id' => 37],
            ['name' => 'extract_update', 'privilege_id' => 38],
            ['name' => 'extract_delete', 'privilege_id' => 39],
            ['name' => 'all_users_read', 'privilege_id' => 40],
            ['name' => 'all_users_create', 'privilege_id' => 41],
            ['name' => 'all_users_update', 'privilege_id' => 42],
            ['name' => 'all_users_delete', 'privilege_id' => 43],
            ['name' => 'config_create', 'privilege_id' => 44],
            ['name' => 'config_update', 'privilege_id' => 45],
            ['name' => 'config_delete', 'privilege_id' => 46],
            ['name' => 'message_create', 'privilege_id' => 47],
            ['name' => 'xtract_support', 'privilege_id' => 48],
            ['name' => 'xtract_admin', 'privilege_id' => 49]
        ];

        Privilege::insert($defaultScopes);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('privilege');
        Schema::enableForeignKeyConstraints();
    }
}
