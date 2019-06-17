<?php

namespace App\Models;

/**
 * Class Version.
 *
 *
 * @SWG\Definition(
 *   definition="Version",
 * )
 */
class Version extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'version';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'installDate';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    //protected $fillable = ['firstname', 'lastname', 'archived'];

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * @SWG\Property(
     *  example="3.55",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="xps_version",
     *  description="XPS version currently installed",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $xps_version;

    /**
     * @SWG\Property(
     *  example="1.43",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="xis_version",
     *  description="XIS version currently installed",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $xis_version;

    /**
     * @SWG\Property(
     *  example="1.07",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="xst_version",
     *  description="XST version currently installed",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $xst_version;

    /**
     * @SWG\Property(
     *  example="1.00",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="schema_version",
     *  description="Schema version currently installed",
     *  minLength=0,
     *  maxLength=5,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $schema_version;

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
