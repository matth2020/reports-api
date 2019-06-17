<?php

namespace App\Models;

/**
 * Class Identification.
 *
 *
 * @SWG\Definition(
 *   definition="Identification",
 * )
 */

class Identification extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'identification';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'identification_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that are hidden from public view.
     *
     * @var array
     */
    protected $hidden = ['identification_id', 'patient_id', 'fmd', 'fmd_construct'];

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]$",
     *  title="finger",
     *  description="Number of the finger associated with this row. 0=left pinky 9=right pinky",
     *  minLength=1,
     *  maxLength=1,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $finger;

    /**
     * Accessors
     */
    public function getIdentificationIdAttribute($value)
    {
        return (int)$value;
    }

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
