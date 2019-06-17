<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLog extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'message_log';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'message_log_id';

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
    protected $hidden = ['message_id'];

    /**
     * Accessors
     */
    public function getContactByAttribute($value)
    {
        switch ($value) {
            case "T,F,F":
                $value = "SMS";
                break;
            case "F,T,F":
                $value = "Email";
                break;
            case "F,F,T":
                $value = "Both";
                break;
            case "F,F,F":
                $value = "No contact";
                break;
            default:
                $value = null;
        }
        return $value;
    }

    /**
     * Mutators
     */
    public function setContactByAttribute($value)
    {
        switch (strtoupper($value)) {
            case "SMS":
                $value = "T,F,F";
                break;
            case "EMAIL":
                $value = "F,T,F";
                break;
            case "BOTH":
                $value = "F,F,T";
                break;
            case "NO CONTACT":
                $value = "F,F,F";
                break;
            default:
                $value = null;
        }
        return $value;
    }

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id');
    }
    public function message()
    {
        return $this->belongsTo('App\Models\Message', 'message_id');
    }

    /**
     * Accessors
     */
    public function getMessageLogIdAttribute($value)
    {
        return (int)$value;
    }

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'content' => 'message',
        'sentTime' => 'sent_time',
        'contactBy' => 'method'
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
