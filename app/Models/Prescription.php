<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Prescription.
 *
 *
 * @SWG\Definition(
 *   definition="Prescription",
 * )
 */

class Prescription extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'prescription';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'prescription_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['prescription_num', 'strikethrough',  'timestamp', 'multiplier', 'customUnits', 'user_id', 'provider_id', 'patient_id', 'clinic_id', 'provider_config_id', '5or10', 'priority', 'prescription_note', 'source', 'external_id'];

    /**
     * The attributes that are hidden from public view.
     *
     * @var array
     */
    protected $hidden = ['provider_signature'];

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Relationships
     */
    public function injections()
    {
        return $this->hasManyThrough('App\Models\Injection', 'App\Models\Compound', 'rx_id', 'compound_id', 'prescription_id', 'compound_id');
    }
    public function compounds()
    {
        return $this->hasMany('App\Models\Compound', 'rx_id');
    }
    public function dosings()
    {
        return $this->hasMany('App\Models\Dosing', 'prescription_id');
    }
    public function extracts()
    {
        return $this->hasMany('App\Models\Dosing', 'prescription_id');
    }
    public function treatmentPlan()
    {
        return $this->belongsTo('App\Models\TreatmentPlan', 'treatment_plan_id');
    }
    public function treatmentPlanDetails()
    {
        return $this->hasMany('App\Models\TreatPlanDetails', 'treatment_plan_id');
    }
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id');
    }
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function providerConfig()
    {
        return $this->belongsTo('App\Models\Profile', 'provider_config_id');
    }
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'provider_config_id');
    }
    public function dosingPlan()
    {
        return $this->belongsTo('App\Models\DosingPlan', 'doseRuleNames_id');
    }

    /**
     * Accessors
     */
    public function getPrescriptionIdAttribute($value)
    {
        return (int)$value;
    }
    public function getSiteAttribute($value)
    {
        switch ($value) {
            case '':
            case -1:
            case null:
                return null;
            default:
                return $value;
        }
    }

    /**
     * Helpers
     */
    public function name()
    {
        $this->name = $this->compounds->sortBy(function ($compound, $key) {
            return $compound->compound_id;
        })[0]->name;
        unset($this->compounds);
        return $this;
    }

    public function everBeenMixed()
    {
        $this->ever_been_mixed = 'F';
        foreach ($this->compounds as $Compound) {
            foreach ($Compound->vials as $Vial) {
                if ($Vial->postponed === 'F') {
                    $this->ever_been_mixed = 'T';
                    return $this;
                }
            }
        }
        return $this;
    }


    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    'prescription_num' => 'prescription_number',
    'customUnits' => 'custom_units',
    'site' => 'injection_site',
    'strikethrough' => 'strike_through',
    '5or10' => 'fold',
    'strikethrough_reason' => 'strike_through_reason',
    'doseRuleNames_id' => 'dosing_plan_id',
    'provider_config_id' => 'profile_id',
    'prescription_note' => 'note'
    );

    public $Messages = [
    'valid_priority' => 'Invalid priority value.',
    'fold.required' => 'An invalid profile prevented calculation of fold.'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
        'note' => array('standard'),
        'provider_signature' => array('standard', 'between:0,45'),
        'multiplier' => array('integer'),
        'patient_id' => array('exists:patient,patient_id,archived,F'),
        'profile_id' => array('exists:provider_config,provider_config_id,deleted,F'),
        'priority' => array('validPriority'),
        'treatment_plan_id' => array('exists:treatment_plan,treatment_plan_id,deleted,F'),
        'source' =>array('in:XPS,xps,XIS,xis,API,api'),
        'dosing_plan_id' => array('exists:doserulenames,doseRuleNames_id,deleted,F'),
        'prescription_note' => array('standard'),
        'strikethrough' => array('in:t,T,f,F'),
        'strikethrough_reason' => array('standard'),
        'clinic_id' => array('exists:clinic,clinic_id,deleted,F'),
        'provider_id' => array('exists:provider,provider_id,deleted,F'),
        'injection_site' => array('in:lowerL,upperL,lowerR,upperR,midL,midR,other')
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['clinic_id', 'provider_id', 'user_id', 'fold'], 'required', function () use ($id) {
            return is_null($id);
        });

        $Validator->sometimes(['profile_id'], 'required_without:outsourced', function () use ($id) {
            return is_null($id);
        });

        $Validator->sometimes(['outsourced'], 'required_without:profile_id,fold,extracts', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
