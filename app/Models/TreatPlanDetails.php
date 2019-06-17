<?php

namespace App\Models;

/**
 * Class TreatPlanDetails.
 *
 *
 * @SWG\Definition(
 *   definition="TreatPlanDetails",
 * )
 */
class TreatPlanDetails extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'treatplandetails';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'treatPlanDetails_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * Relationships
     */
    public function treatmentPlan()
    {
        return $this->belongsTo('App\Models\TreatmentPlan', 'treatment_plan_id');
    }

    /**
     * Mutators to alter data before saving to DB.
     */
    public function setColorAttribute($value)
    {
        switch ($value) {
            case 'RED':
                $value = '16711680';
                break;
            case 'ORNG':
                $value = '16748842';
                break;
            case 'YLW':
                $value = '16776960';
                break;
            case 'WHT':
                $value = '16448250';
                break;
            case 'GRN':
                $value = '65280';
                break;
            case 'LTGR':
                $value = '7470974';
                break;
            case 'LTBL':
                $value = '7471097';
                break;
            case 'SLVR':
                $value = '13684944';
                break;
            case 'BLUE':
                $value = '255';
                break;
            case 'PRPL':
                $value = '4456618';
                break;
            case 'PINK':
                $value = '16759010';
                break;
            default:
                $value = 'unknown color';
                break;
        }
        $this->attributes['color'] = $value;
    }

    /**
     * Accessors.
     */
    public function getColorAttribute($value)
    {
        switch ($value) {
            case '16711680':
                return 'RED';
            case '16748842':
                return 'ORNG';
            case '16776960':
                return 'YLW';
            case '16448250':
                return 'WHT';
            case '65280':
                return 'GRN';
            case '7470974':
                return 'LTGR';
            case '7471097':
                return 'LTBL';
            case '13684944':
                return 'SLVR';
            case '255':
                return 'BLUE';
            case '4456618':
            case '12609535':
            case '12612095':
                return 'PRPL';
            case '13273343':
            case '16759010':
                return 'PINK';
            default:
                return $value;
        }
    }

    public function getTreatPlanDetailsIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    public $Messages = [
        'fold.in' => 'The :attribute must be one of the following values: :values.',
        'dilution.in' => 'The :attribute must be one of the following values: :values.',
        'color.color' => 'The color must be one of BLUE, GRN, LTBL, LTGR, ORNG, PINK, PRPL, RED, SLVR, WHT, or YLW.'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'dilution' => 'in:0,1,10,100,1000,10000,100000,1000000,10000000',
            'fold' => 'in:5,10',
            'color' => 'color'
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['dilution','fold','color'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
