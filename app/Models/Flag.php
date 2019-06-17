<?php

namespace App\Models;

/**
 * Class Flag.
 *
 *
 * @SWG\Definition(
 * )
 */
class Flag extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'flag';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'flag_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="flag_id",
     *  description="Id of the Flag from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $flag_id;

    /**
     * @SWG\Property(
     *  example="Clerical",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="category",
     *  description="Flag category",
     *  minLength=0,
     *  maxLength=16,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $category;

    /**
     * @SWG\Property(
     *  example="active",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="status",
     *  description="active | inactive | entered-in-error",
     *  minLength=0,
     *  maxLength=16,
     *  type={"string","null"},
     *  enum={"active","inactive","entered-in-error"},
     *  default="active",
     * )
     *
     * @var string
     */
    private $status;

    /**
     * @SWG\Property(
     *  example="2017-01-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) ((2[0-4])|(1[0-9])|(0[1-9])):([0-6][0-9]):([0-6][0-9])$",
     *  title="period_start",
     *  description="When the flag should start being visible",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $period_start;

    /**
     * @SWG\Property(
     *  example="2017-08-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) ((2[0-4])|(1[0-9])|(0[1-9])):([0-6][0-9]):([0-6][0-9])$",
     *  title="period_end",
     *  description="When the flag should stop being visible",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $period_end;

    /**
     * @SWG\Property(
     *  example="30",
     *  title="period_interval",
     *  description="minimum number of days between acknowledged alerts.",
     *  minLength=10,
     *  maxLength=10,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $period_interval;

    /**
     * @SWG\Property(
     *  example="Patient pays by credit card",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="code",
     *  description="Content of the flag",
     *  minLength=0,
     *  maxLength=255,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $code;

    /**
     * @SWG\Property(
     *  example="Payment",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="identifier",
     *  description="Effectively acts as shorthand or a name for the flag",
     *  minLength=0,
     *  maxLength=32,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $identifier;

    /**
     * @SWG\Property(
     *  example="2017-08-23 01:23:20",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) ((2[0-4])|(1[0-9])|(0[1-9])):([0-6][0-9]):([0-6][0-9])$",
     *  title="last_alert",
     *  description="The last time the flag was acknowledged",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $last_alert;

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }

    /**
     * Accessors
     */
    public function getFlagIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'catagory' => 'category'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'category' => array('standard', 'between:0,16'),
            'status' => array('standard', 'between:0,16'),
            'patient_id' => array('integer', 'in:'.$data['patient_id']),
            'code' => array('standard', 'between:0,255'),
            'identifier' => array('standard', 'between:0,32'),
            'period_start' => array('date_format:Y-m-d'),
            'period_end' => array('date_format:Y-m-d'),
            'last_alert' => array('date_format:Y-m-d H:i:s'),
            'period_interval' => array('integer'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['category', 'status'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
