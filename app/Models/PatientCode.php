<?php

namespace App\Models;

class PatientCode extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'flaguse';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'flagUse_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }

    public function code()
    {
        return $this->belongsTo('App\Models\Code', 'flag_id');
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'flag_id' => 'code_id',
        'flagUse_id' => 'code_use_id'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'patient_id' => array('exists:patient,patient_id,archived,F'),
            'code_id' => array('exists:flags,flag_id,deleted,F')
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['patient_id', 'flag_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
