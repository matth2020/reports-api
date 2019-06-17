<?php

namespace App\Models;

/**
 * Class PlanBottle.
 *
 *
 * @SWG\Definition(
 *   definition="PlanBottle",
 * )
 */
class PlanBottle extends BaseModel
{
    /**
    * @SWG\Property(
    *  example="100",
    *  pattern="^[0-9]+$",
    *  title="dilution",
    *  description="Dilution of the bottle",
    *  minLength=0,
    *  maxLength=45,
    *  type={"integer","null"},
    *  default="null",
    * )
    *
    * @var int
    */
    private $dilution;

    /**
     * @SWG\Property(
     *  example="null",
     *  pattern="",
     *  title="color",
     *  description="Color associated with this dilution (Supported colors are RED,YLW,BLUE,GRN,SLVR,ORNG,PRPL,WHT,LTGR,LTBL,PINK,GOLD).",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $color;

    /**
     * @SWG\Property(
     *  example="10.000",
     *  pattern="^[0-9]{1,4}(\.[0-9]{1,3})?$",
     *  title="size",
     *  description="Size of patient vial",
     *  minLength=0,
     *  maxLength=7,
     *  type={"number","null"},
     *  default="",
     * )
     *
     * @var float
     */
    private $size;

    /**
     * @SWG\Property(
     *  example={"dilution":"1","color":"16711680","size":"5.00","steps":{"dose":"0.2","min_interval":"5","max_interval","10"}},
     *  pattern="",
     *  title="plan",
     *  description="An array of objects describing the dosing steps for the associated bottle.",
     *  type="array",
     *  @SWG\Items(ref="#/definitions/PlanStep")
     * )
     * @var plan   */
    private $steps;

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
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
