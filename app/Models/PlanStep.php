<?php

namespace App\Models;

/**
 * Class PlanStep.
 *
 *
 * @SWG\Definition(
 *   definition="PlanStep",
 * )
 */
class PlanStep extends BaseModel
{
    /**
     * @SWG\Property(
     *  example="0.20",
     *  pattern="^[0-9]{1,3}(\.[0-9]{1,2})?$",
     *  title="dose",
     *  description="Prescriptions dose for this extract",
     *  minLength=0,
     *  maxLength=5,
     *  default="null",
     * )
     *
     * @var float
     */
    private $dose;

    /**
     * @SWG\Property(
     *  example="10",
     *  pattern="^[0-9]+$",
     *  title="min_interval",
     *  description="Minimum number of days before injection",
     *  minLength=0,
     *  maxLength=45,
     *  default="0",
     * )
     *
     * @var int
     */
    private $min_interval;

    /**
     * @SWG\Property(
     *  example="14",
     *  pattern="^[0-9]+$",
     *  title="min_interval",
     *  description="Maximum number of days before injection",
     *  minLength=0,
     *  maxLength=45,
     *  default="0",
     * )
     *
     * @var int
     */
    private $max_interval;

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    public $Messages = [
        'dose.decimal63' => 'The dose must be in the format ddd.ddd.'
    ];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'step_number' => 'integer',
            'min_interval' => 'integer|min:0',
            'max_interval' => 'integer|min:0',
            'dose' => 'numeric|decimal63|min:0.00'
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['dose','min_interval','max_interval', 'step_number'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
