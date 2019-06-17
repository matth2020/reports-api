<?php

namespace App\Models;

/**
 * Class PatientTemp.
 *
 *
 * @SWG\Definition(
 *   definition="PatientTmp"
 * )
 */
class PatientTemp extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'patient_temp';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'patient_temp_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    protected $fillable = [
        'firstname',
        'lastname',
        'mi',
        'dob',
        'phone',
        'addr1',
        'addr2',
        'city',
        'state',
        'zip',
        'displayName',
        'chart',
        'MSHsegment',
        'patient_notes',
        'eContact',
        'eContactNum',
        'email',
        'smsphone',
        'home_phone',
        'guar_last',
        'guar_first',
        'guar_mi',
        'guar_suffix',
        'guar_addr1',
        'guar_addr2',
        'guar_city',
        'guar_state',
        'guar_zip',
        'prim_carrier',
        'sec_carrier',
        'PIDsegment',
        'PV1segment',
        'MRGsegment',
        'hl7message',
        'gender',
        'ssn'];

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array();

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        return $Validator;
    }
}
