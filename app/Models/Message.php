<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'message';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'message_id';

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
    protected $hidden = ['sql_code','name'];

    /**
     * Relationships
     */
    public function contactLogs()
    {
        return $this->hasMany('App\Models\ContactLogs', 'message_log_id');
    }

    /**
     * Accessors
     */
    public function getMessageIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'content' => 'message'
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
