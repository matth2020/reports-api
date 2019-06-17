<?php

namespace App\Models;

use App\Models\BaseModel;

class UserGroupUser extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'user_group_user';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = ['user_group_id','user_id'];
    public $incrementing = false;

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'user_group_id' => ['integer', 'exists:user_group,user_group_id'],
            'user_id' => ['integer', 'exists:user,user_id']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes([], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
