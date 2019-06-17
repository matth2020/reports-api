<?php

namespace App\Models;

use Illuminate\Validation\Validator;
use Carbon\Carbon;

/**
 * Class Inventory.
 *
 *
 * @SWG\Definition(
 *   definition="Inventory",
 *   required={"extract_id"}
 * )
 */
class Inventory extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'inventory';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'inventory_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = true;

    /**
     * Relationships
     */
    public function vials()
    {
        return $this->hasMany('App\Models\Vial', 'inventory_id');
    }
    public function extract()
    {
        return $this->belongsTo('App\Models\Extract', 'extract_id');
    }

    /**
     * Accessors
     */


    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'outdate' => 'out_date',
        'lotnumber' => 'lot_number',
        'dilutionENT' => 'dilution_ent',
        'vialSize' => 'vial_size',
        'volumeNew' => 'volume_new',
        'volumeCurrent' => 'volume_current',
        'installtime' => 'install_time',
        'removetime' => 'remove_time',
        'changereason' => 'change_reason',
        'discardDate' => 'discard_date',
        'percentHSA' => 'percent_hsa',
        'percentPhenol' => 'percent_phenol',
        'percentGlycerin' => 'percent_glycerin',
        'installBy' => 'install_by',
        'removeBy' => 'remove_by'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'barcode' => ['standard', 'between:0,45'],
            'out_date' => 'date_format:Y-m-d',
            'lot_number' => ['standard', 'between:0,45'],
            'dilution_ent' => ['integer','between:0,11'],
            'vial_size' => 'decimal73',
            'volume_new' => 'decimal73',
            'volume_current' => 'decimal73',
            'install_time' => 'date_format:Y-m-d H:i:s',
            'remove_time' => 'date_format:Y-m-d H:i:s',
            'change_reason' => ['change_reason','between:0,150'],
            'timestamp' => 'date_format:Y-m-d H:i:s',
            'discard_date' => 'date_format:Y-m-d',
            'door' => 'integer',
            'page' => 'integer',
            'location' => 'integer',
            'percent_hsa' => 'decimal52',
            'percent_glycerin' => 'decimal52',
            'percent_phenol' => 'decimal52',
            'deleted' => 'in:t,T,f,F',
            'extract_id' => 'exists:extract,extract_id,deleted,F',
            'install_by' => 'exists:user,user_id,deleted,F',
            'remove_by' => 'exists:user,user_id,deleted,F'

        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['extract_id','lot_number','out_date','door','page'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }

    public function markDeleted($RequestOptions)
    {
        $this->removeBy = $RequestOptions->user_id;
        $this->removetime = Carbon::now()->toDateTimeString();
        $this->deleted = 'T';
        $this->door = -1;
        $this->save();
        return $this;
    }
}
