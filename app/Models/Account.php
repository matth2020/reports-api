<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Account.
 *
 *
 * @SWG\Definition(
 *   definition="Account"
 * )
 */
class Account extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'account';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'account_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * relationships
     */
    public function patients()
    {
        return $this->hasMany('App\Models\Patient', 'account_id');
    }
    public function billingAddress()
    {
        return $this->belongsTo('App\Models\Address', 'bill_to', 'address_id');
    }
    public function shippingAddress()
    {
        return $this->belongsTo('App\Models\Address', 'ship_to', 'address_id');
    }
    public function primaryAddress()
    {
        return $this->belongsTo('App\Models\Address', 'address_id', 'address_id');
    }

    public function markDeleted($RequestOptions)
    {
        $this->setArchivedAttribute('T');
        $this->save();
        
        return $this;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array();

    protected function makeValidationRules($id, $data = null)
    {
        // Keep validation intentially loose... if it fits in the db
        // we should accept it... some log is better than no log.
        // We will trim strings if necessary in the controller.
        $Rules = [
            'notes' => 'standard',
            'reference_number' => ['between:0,128','standard'],
            'account_status_id' => 'exists:account_status,account_status_id',
            'account_number' => ['between:0,64','standard'],
            'name' => ['between:0,64', 'standard'],
            'address_id' => 'exists:address,address_id',
            'bill_to' => 'exists:address,address_id',
            'ship_to' => 'exists:address,address_id',
            'phone' => 'phone',
            'archived' => 'in:t,T,f,F'
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
