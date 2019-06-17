<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Xisprefs.
 *
 *
 * @SWG\Definition(
 *   definition="Xisprefs",
 *   required={"name"}
 * )
 */
class Xisprefs extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'xisprefs';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'xisprefs_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array();

    /**
     * helper functions
     */
    public static function getPrefBit($PrefsetNum, $BitNum)
    {
        $BitArr = explode(',', Xisprefs::first()->{'prefSet'.$PrefsetNum});
        return $BitArr[$BitNum];
    }

    public static function setPrefBit($PrefsetNum, $BitNum, $Value)
    {
        $Row = Xisprefs::first();
        $BitArr = explode(',', $Row->{'prefSet'.$PrefsetNum});
        $BitArr[$BitNum] = strtoupper($Value) === 'T' ? 'T' : 'F';
        $Row->{'prefSet'.$PrefsetNum} = implode(',', $BitArr);
        $Row->save();
        return;
    }

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [];
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
