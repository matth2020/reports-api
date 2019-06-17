<?php

namespace App\Models;

/**
 * Class DosingPlanSet.
 *
 *
 * @SWG\Definition(
 *   definition="DosingPlanSet",
 * )
 */
class DosingPlanSet extends BaseModel
{
    /**
    * @SWG\Property(
    *  example="LOCAL",
    *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
    *  title="reaction_type",
    *  description="The type of reaction the rule applies to",
    *  minLength=0,
    *  maxLength=45,
    *  type={"string","null"},
    *  enum={"LOCAL","SYSTEMIC"},
    *  default="null",
    * )
    *
    * @var string
    */
    private $reaction_type;

    /**
     * @SWG\Property(
     *  example="0-2",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="reaction_value",
     *  description="The value of reaction the rule applies to",
     *  minLength=0,
     *  maxLength=20,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $reaction_value;

    /**
     * @SWG\Property(
     *  example={},
     *  pattern="",
     *  title="adjustments",
     *  description="An array of objects describing set of dosing rules for the plan",
     *  type="array",
     *         @SWG\Items(type="string")
     * )
     * @var string   */
    private $adjustments;

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'reaction_type' => array('in:local,Local,LOCAL,systemic,Systemic,SYSTEMIC'),
            'reaction_value' => array('standard', 'inReactions:any', 'between:0,20'),
            'adjustments' => array('array', 'validDosingAdjustments'),
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['reaction_type','reaction_value','adjustments'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
