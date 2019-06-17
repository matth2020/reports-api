<?php

namespace App\Models;

/**
 * Class TreatmentPlan.
 *
 *
 * @SWG\Definition(
 *   definition="TreatmentPlan",
 *   required={"name"}
 * )
 */
class TreatmentPlan extends BaseModel
{
    /**
    * @SWG\Property(
    *  example="1",
    *  pattern="^[0-9]+$",
    *  title="treatment_plan_id",
    *  description="Id of the treatment_plan from the database.",
    *  minLength=0,
    *  maxLength=11,
    *  type={"integer","null"},
    *  default=""
    * )
    *
    * @var int
    */
    private $treatment_plan_id;

    /**
     * @SWG\Property(
     *  example="0.5 red standard",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Name of the treatment_plan",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="maint_steps_back",
     *  description="Number of steps to fall back when a maintenance bottle change occurs.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default=""
     * )
     *
     * @var int
     */
    private $maint_steps_back;

    /**
     * @SWG\Property(
     *  example={"dilution":"1","color":"16711680","size":"5.00","steps":{"dose":"0.2","min_interval":"5","max_interval","10"}},
     *  pattern="",
     *  title="details",
     *  description="An array of objects describing each bottle of the treatment plan",
     *  type="array",
     *  @SWG\Items(ref="#/definitions/PlanBottle")
     * )
     * @var details   */
    private $details;

    /**
     * @SWG\Property(
     *  example="F",
     *  pattern="^[tTfF]{1}$",
     *  title="deleted",
     *  description="Is the plan deleted in the system (t/f)",
     *  minLength=1,
     *  maxLength=1,
     *  type={"string","null"},
     *  enum={"F","T"},
     *  default="F",
     * )
     *
     * @var string
     */
    private $deleted;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'treatment_plan';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'treatment_plan_id';

    /**
     * The attributes that are not visible to the public.
     *
     * @var array
     */

    protected $hidden = ['type'];

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    public $Messages = [
        'details.valid_tp_details' => 'There are errors in the details.',
        'details.treatment_plan_steps_zero_based' => 'Treatment plan steps must start at zero.',
        'details.treatment_plan_steps_increase' =>  'Treatment plan steps must increase by one.',
        'details.treatment_plan_doses_increase' =>  'Treatment plan doses must increase each step within a dilution.'
    ];

    /**
     * Relationships
     */
    public function injections()
    {
        return $this->hasMany('App\Models\Injection', 'treatment_plan_id');
    }
    public function prescriptions()
    {
        return $this->hasMany('App\Models\Prescription', 'treatment_plan_id');
    }
    public function dosingPlan()
    {
        return $this->belongsTo('App\Models\DosingPlan', 'dosing_plan_id');
    }
    public function treatmentPlanDetails()
    {
        return $this->hasMany('App\Models\TreatPlanDetails', 'treatment_plan_id');
    }
    public function vials()
    {
        return $this->hasMany('App\Models\Vial', 'treatment_plan_id');
    }

    /**
     * Accessors.
     */
    public function getMaintStepsBackAttribute($value)
    {
        if (is_null($value) || $value == 'NULL') {
            return null;
        } else {
            return (int) $value;
        }
    }

    public function getTreatmentPlanIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'doserulenames_id' => 'dosing_plan_id',
        'maint_steps_back' => 'maintenance_steps_back'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => ['unique:treatment_plan,name,'.$id.',treatment_plan_id,deleted,F','standard','between:1,255'],
            'maintenance_steps_back' => 'integer|min:-10|max:0',
            'details' => ['array','validTpDetails','treatmentPlanStepsZeroBased','treatmentPlanStepsIncrease','treatmentPlanDosesIncrease'],
            'deleted' => ['standard','in:t,T,f,F'],
            'dosing_plan_id' => ['exists:doserulenames,doserulenames_id,deleted,F']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name','maintenance_steps_back','details','dosing_plan_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }

    /**
     * Create an array of all (dilution, step_number, dose) triples from details
     * @param $details
     * @return array
     */
    public static function flattenDetails($details)
    {
        $allSteps = [];
        foreach ($details as $key => $dilLevel) {
            foreach ($dilLevel['steps'] as $stepkey => $step) {
                $allSteps[] = [
                    'dilution' => $dilLevel['dilution'],
                    'step_number' => $step['step_number'],
                    'dose' => $step['dose']
                ];
            }
        }
        return $allSteps;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public static function cmpStepNum($a, $b)
    {
        // expects array of (dilution, step_num, dose) triples
        // sort descending by dilution, ascending by step_num
        if ($a['dilution'] == $b['dilution']) {
            if ($a['step_number'] == $b['step_number']) {
                return 0;
            }
            return ($a['step_number'] < $b['step_number']) ? -1 : 1;
        }
        return ($a['dilution'] > $b['dilution']) ? -1 : 1;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    public static function cmpStepDose($a, $b)
    {
        // expects array of (dilution, step_num, dose) triples
        // sort descending by dilution, ascending by dose
        if ($a['dilution'] == $b['dilution']) {
            if ($a['dose'] == $b['dose']) {
                // tps can now allow two doses that are the same within a dilution
                // for the sake of comparing the resulting array, we need to refer
                // to the step here so that we can make sure we chose the correct
                // order for that case
                if ($a['step_number'] == $b['step_number']) {
                    return 0;
                }
                return $a['step_number'] < $b['step_number'] ? -1 : 1;
            }
            return ($a['dose'] < $b['dose']) ? -1 : 1;
        }
        return ($a['dilution'] > $b['dilution']) ? -1 : 1;
    }
}
