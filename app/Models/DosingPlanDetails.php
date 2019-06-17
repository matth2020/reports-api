<?php

namespace App\Models;

/**
 * Class DosingPlanDetails.
 *
 *
 * @SWG\Definition(
 *   definition="DosingPlanDetails",
 * )
 */
class DosingPlanDetails extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'doseruledetails';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'doseRuleDetails_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Columns to remain hidden.
     *
     * @var array
     */
    protected $hidden = ['oldStyle', 'doseRuleDetails_id'];

    /**
     * Relationships
     */
    public function dosingPlan()
    {
        return $this->belongsTo('App\Models\DosingPlan', 'doseRuleNames_id');
    }

    /**
     * Mutators to alter data before saving to DB.
     */
    public function setDeltaAttribute($value)
    {
        $this->attributes['delta'] = strtoupper((string) $value);
    }

    public function setReactTypeAttribute($value)
    {
        switch (strtoupper($value)) {
            case 'LOCAL':
                $value = 'L';
                break;
            case 'SYSTEMIC':
                $value = 'S';
                break;
            default:
        }
        $this->attributes['reactType'] = $value;
    }

    /**
     * Accessors.
     */
    public function getReactTypeAttribute($value)
    {
        switch (strtoupper($value)) {
            case 'L':
                return 'LOCAL';
            case 'S':
                return 'SYSTEMIC';
            default:
                return $value;
        }
    }

    public function getDoseRuleDetailsIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'doseruledetails_id' => 'dosing_plan_detail_id',
        'reactType' => 'reaction_type',
        'reactVal' => 'reaction_value'
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
