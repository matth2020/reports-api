<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Vial.
 *
 *
 * @SWG\Definition(
 *   definition="Vial",
 *   required={"name"}
 * )
 */
class Vial extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'vial';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'vial_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    public $hidden = ['bottleNote'];

    /**
     * Relationships
     */
    public function treatmentPlan()
    {
        return $this->belongsTo('App\Models\TreatmentPlan', 'treatment_plan_id');
    }
    public function dosing()
    {
        return $this->belongsTo('App\Models\Dosing', 'dosing_id');
    }
    public function inventory()
    {
        return $this->belongsTo('App\Models\Inventory', 'inventory_id');
    }
    public function compound()
    {
        return $this->belongsTo('App\Models\Compound', 'compound_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Accessors
     */
    public function getVialIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'outdate' => 'out_date',
        'mixdate' => 'mix_date',
        'traylocation' => 'tray_location',
        'bottleNote' => 'bottle_note',
        'mixAfter' => 'mix_after',
        'labelOutdate' => 'label_out_date',
        'diltPos' => 'diluent_position',
        'sterilityStart' => 'sterility_start',
        'sterilityEnd' => 'sterility_end',
        'shipDate' => 'ship_date',
        'approvalDate' => 'approval_date',

    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'barcode' => array('integer', 'validVialBarcode:'.$data['compound_id']),
            'tray_location' => array('standard','between:0,45'),
            'bottle_note' => array('standard','between:0,225'),
            'out_date' => 'date_format:Y-m-d',
            'mix_date' => 'date_format:Y-m-d H:i:s',
            'mix_after' => 'date_format:Y-m-d',
            'label_outdate' => 'date_format:Y-m-d',
            'sterility_start' => 'date_format:Y-m-d',
            'sterility_end' => 'date_format:Y-m-d',
            'ship_date' => 'date_format:Y-m-d',
            'approval_date' => 'date_format:Y-m-d',
            'postponed' => 'in:T,t,F,f',
            'transaction' => 'integer',
            'user_id' => 'exists:user,user_id,deleted,F'
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['compound_id','barcode','transaction','dosing_id','inventory_id','user_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        $Data = $Validator->getData();
        // these make it possible for the api to ignore ids in the case that the rows
        // are being created as part of a larger transaction. In this case validation
        // would fail because the other ids in the transaction dont "exist" until the
        // transaction is complete therefor the API must disable checking and be
        // responsible for supplying valid ids.
        $Validator->sometimes('dosing_id', 'exists:dosing,dosing_id', function () use ($Data) {
            return $Data['ignore_ids'] !== true;
        });
        $Validator->sometimes('inventory_id', 'exists:inventory,inventory_id,deleted,F', function () use ($Data) {
            return $Data['ignore_ids'] !== true;
        });
        $Validator->sometimes('compound_id', 'exists:compound,compound_id', function () use ($Data) {
            return $Data['ignore_ids'] !== true;
        });

        return $Validator;
    }
}
