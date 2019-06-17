<?php

namespace App\Models;

/**
 * Class TrackingValue.
 *
 *
 * @SWG\Definition(
 *   definition="TrackingValue",
 *   required={"patient_id","tracking_name"}
 * )
 */

class TrackingValue extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'tracking';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'tracking_id';

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
     *  title="tracking_value_id",
     *  description="Id of the tracking value from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $tracking_value_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="patient_id",
     *  description="Id of the patient that the trackingvalue object applies to.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $patient_id;

    /**
     * @SWG\Property(
     *  example="peak flow",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="tracking_name",
     *  description="A tracking name matching one of the names in the system/patient tracking config.",
     *  minLength=0,
     *  maxLength=32,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $tracking_name;

    /**
     * @SWG\Property(
     *  example="1973-08-23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9]))$",
     *  title="timestamp",
     *  description="Date/time the value was observed.",
     *  minLength=10,
     *  maxLength=10,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $timestamp;

    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^-?[0-9]{1,4}(\.[0-9]{1,2})?$",
     *  title="value",
     *  description="Floating point number between -999.99 and +999.99",
     *  minLength=0,
     *  maxLength=6,
     *  default="null",
     * )
     *
     * @var float
     */
    private $value;

    /**
     * Accessors
     */
    public function getTrackingValueIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'tracking_id' => 'tracking_value_id',
        'trackingName' => 'tracking_name',
        'date' => 'timestamp'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'tracking_name' => array('standard', 'between:0,32', 'exists:config,name,section,trackingNames'),
            'patient_id' => array('required', 'integer', 'exists:patient,patient_id,archived,F'),
            'value' => array('decimal63', 'between:0,16'),
            'timestamp' => array('date_format:Y-m-d H:i:s'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['tracking_name','value'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }

    public function markDeleted($RequestOptions)
    {
        $this->delete();
        
        return $this;
    }

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
}
