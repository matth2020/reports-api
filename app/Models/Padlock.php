<?php

namespace App\Models;

use Illuminate\Validation\Validator;

class Padlock extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'padlock';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'lock_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->hasOne('App\Models\Patient', 'lock_id');
    }
    public function patientConfig()
    {
        return $this->hasOne('App\Models\PatientConfig', 'lock_id', 'lock_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'locked_by', 'user_id');
    }

    /**
     * Accessors
     */
    public function getLockIdAttribute($value)
    {
        return (int)$value;
    }

    public function markDeleted($RequestOptions)
    {
        if (!is_null($this->patientConfig)) {
            $this->patientConfig->delete();
        }
        if (!is_null($this->patient)) {
            $this->patient->lock_id = null;
            $this->patient->save();
        }
        $this->delete();
        
        return $this;
    }

    /**
     * An array of fields that need to be converted from one name
     * in the database (array index) to another in the json object (value).
     */
    public static $DBtoRestConversion = array(
        'locked_by' => 'user_id'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [];
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
