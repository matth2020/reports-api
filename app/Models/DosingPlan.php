<?php

namespace App\Models;

/**
 * Class DosingPlan.
 *
 *
 * @SWG\Definition(
 *   definition="DosingPlan",
 *   required={"name"}
 * )
 */
class DosingPlan extends BaseModel
{
    /**
    * @SWG\Property(
    *  example="1",
    *  pattern="^[0-9]+$",
    *  title="doserulenames_id",
    *  description="Id of the treatment_plan from the database.",
    *  minLength=0,
    *  maxLength=11,
    *  type={"integer","null"},
    *  default=""
    * )
    *
    * @var int
    */
    private $doserulenames_id;

    /**
     * @SWG\Property(
     *  example="Standard dosing",
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
     *  example={},
     *  pattern="",
     *  title="plan",
     *  description="An array of objects describing set of dosing rules for the plan",
     *  type="array",
     *  @SWG\Items(ref="#/definitions/DosingPlanSet")
     * )
     * @var plan   */
    private $plan;

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
    protected $table = 'doserulenames';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'doseRuleNames_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Relationships
     */
    public function dosingPlanDetails()
    {
        return $this->hasMany('App\Models\DosingPlanDetails', 'doseRuleNames_id');
    }
    public function prescription()
    {
        return $this->hasMany('App\Models\Prescription', 'doseRuleNames_id');
    }
    public function treatmentPlans()
    {
        return $this->hasMany('App\Models\TreatmentPlan', 'doseRuleNames_id');
    }

    /**
     * Accessors
     */
    public function getDoseRuleNamesIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'doseRuleNames_id' => 'dosing_plan_id'
    );

    public $Messages = [
        'plan.has_all_reaction_types' => 'Must have a :attribute for each reaction type'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => array('standard', 'between:1,255','unique:doserulenames,name,' . $id . ',doserulenames_id,deleted,F'),
            'plan' => array('array','hasAllReactionTypes','validDosingSets'),
            'deleted' => array('standard', 'in:t,T,f,F'),
        ];

        // add an error message replacer to handle plan validation

        \Validator::replacer('hasAllReactionTypes', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', implode(', ', $parameters), $message);
        });

        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name', 'plan'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
