<?php

namespace App\Models;

use DB;

/**
 * Class Skintest.
 *
 *
 * @SWG\Definition(
 *   definition="Skintest",
 * )
 */

class Skintest extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'skintest';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'skintest_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that are hidden from public view.
     *
     * @var array
     */
    protected $hidden = ['patient_id','provider_id','user_id','provider_config_id','updated_at','external_id','test_log'];

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider', 'provider_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function protocol()
    {
        return $this->belongsTo('App\Models\Protocol', 'protocol_id');
    }
    public function scores()
    {
        return $this->hasMany('App\Models\Score', 'skintest_id');
    }

    /**
     * Accessors.
     */
    public function getStateAttribute($value)
    {
        $value = Config::where('section', 'test_status')
            ->where('name', $value)
            ->pluck('value')
            ->first();
        return $value;
    }

    public function getSkintestIdAttribute($value)
    {
        return (int)$value;
    }
    
    // XA: Skintest report: Number of Antigens in a skin test
    public function getAntigensAttribute()
    {
        $result = DB::table('score')
        ->where('skintest_id', '=', $this->skintest_id)
        ->groupby('skintest_id')
        ->count();

        return $result;
        //return rand ( 1 , 99 );
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'adjby' => 'adjusted_by',
        'reviewby' => 'reviewed_by'
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
