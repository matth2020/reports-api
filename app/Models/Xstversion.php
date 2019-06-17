<?php

namespace App\Models;

/**
 * Class Xstversion.
 *
 *
 * @SWG\Definition(
 *   definition="Xstversion",
 *   required={"name"}
 * )
 */
class Xstversion extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'xstversion';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'updated_at';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
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
