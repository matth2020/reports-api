<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * Class Config.
 *
 *
 * @SWG\Definition(
 *   definition="UserGroup",
 *   required={"name"}
 * )
 */

class UserGroup extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'user_group';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_group_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * relationships
     */
    public function users()
    {
        return $this->hasManyThrough('App\Models\User', 'App\Models\UserGroupUser', 'user_group_id', 'user_id', 'user_group_id', 'user_id');
    }
    public function privileges()
    {
        return $this->hasManyThrough('App\Models\Privilege', 'App\Models\UserGroupPrivilege', 'user_group_id', 'privilege_id', 'user_group_id', 'privilege_id');
    }

    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => ['standard', 'between:0,16','unique:user_group,name,'.$id.',user_group_id']
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
