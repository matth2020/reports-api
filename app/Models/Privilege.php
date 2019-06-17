<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * Class Privilege.
 *
 *
 * @SWG\Definition(
 *   definition="Privilege",
 * )
 */

class Privilege extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'privilege';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'privilege_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    public static $DBtoRestConversion = [];

    /**
     * relationships
     */
    public function userGroups()
    {
        return $this->hasManyThrough('App\Models\UserGroup', 'App\Models\UserGroupPrivilege', 'privilege_id', 'user_group_id', 'privilege_id', 'user_group_id');
    }

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => ['standard', 'between:0,16','unique:privilege,name,'.$id.',privilege_id']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
