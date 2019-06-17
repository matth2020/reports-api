<?php

namespace App\Models;

/**
 * Class Report.
 *
 *
 * @SWG\Definition(
 * )
 */
class Report extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'reports';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'reports_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    protected $with = ['prints','user'];

    protected $hidden = ['document', 'xml', 'user_id'];

    /**
     * Relationships
     */
    public function template()
    {
        return $this->belongsTo('App\Models\Template', 'template_id');
    }

    public function prints()
    {
        return $this->hasMany('App\Models\PrintQueue', 'reports_id', 'reports_id');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User', 'user_id', 'user_id');
    }


    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'reports_id' => 'report_id',
        'xml' => 'json',
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        return $Validator;
    }
}
