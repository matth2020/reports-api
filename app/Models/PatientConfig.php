<?php

namespace App\Models;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

/**
 * Class Config.
 *
 *
 * @SWG\Definition(
 *   definition="PatientConfig",
 *   required={"name","app","value"}
 * )
 */

class PatientConfig extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'patient_config';
    public $exists = false;

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'patient_config_id';

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
     *  title="patient_config_id",
     *  description="Id of the config object from the database.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $patient_config_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="patient_id",
     *  description="Id of the patient the config object belongs to.",
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
     *  example="sizes",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Name of the config item",
     *  minLength=0,
     *  maxLength=32,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="5ml,10ml,15ml",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="value",
     *  description="Value of the config item",
     *  minLength=0,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $value;

    /**
     * Accessors
     */
    public function getConfigIdAttribute($value)
    {
        return (int)$value;
    }

    public function padlock()
    {
        return $this->belongsTo('App\Models\Padlock', 'lock_id', 'lock_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id', 'patient_id');
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    public $Messages = [
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $value = isset($data['value']) ? $data['value'] : null;
        $app = isset($data['app']) ? $data['app'] : null;

        $PatientId = $data['patient_id'];
        $Value = isset($data['value']) ? $data['value'] : null;
        
        $Rules = [
            'patient_id' => array('exists:patient,patient_id,archived,F'),
            'name' => array('standard', 'between:0,32', 'validPatientLock:'.$PatientId.','.$Value),
            'value' => array('standard'),
        ];

        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['patient_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }

    public function markDeleted($RequestOptions)
    {
        $this->delete();
        return $this;
    }
}
