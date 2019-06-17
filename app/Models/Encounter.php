<?php

namespace App\Models;

use Illuminate\Validation\Validator;

/**
 * Class Encounter.
 *
 *
 * @SWG\Definition(
 *   definition="Encounter",
 * )
 */
class Encounter extends Login
{
    /**
    * @SWG\Property(
    *  example="1",
    *  pattern="^[0-9]+$",
    *  title="waitlist_id",
    *  description="Id of the login entry from the database.",
    *  minLength=0,
    *  maxLength=11,
    *  type={"integer","null"},
    *  default=""
    * )
    *
    * @var int
    */
    private $waitlist_id;

    /**
     * @SWG\Property(
     *  example="John Doe",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="name",
     *  description="Patient approved (HIPPA compliant) identification name",
     *  minLength=0,
     *  maxLength=45,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="2016-08-30 13:27:53",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) (2[0-4]|[0-1][0-9])(:([0-5][0-9]|60)){2}$",
     *  title="login_time",
     *  description="Date/time when patient logged in at kiosk",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $login_time;

    /**
     * @SWG\Property(
     *  example="2016-08-30 13:57:23",
     *  pattern="^(19|20){1}[0-9]{2}-((1[0-2])|(0?[0-9]{1}))-((3[0-1])|([1-2][0-9])|(0?[0-9])) (2[0-4]|[0-1][0-9])(:([0-5][0-9]|60)){2}$",
     *  title="excuse_time",
     *  description="Date/time when nurse scheduled patient to be excused",
     *  minLength=19,
     *  maxLength=19,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $excuse_time;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="patient_id",
     *  description="Id of the patient associated with this login event.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     * )
     *
     * @var int
     */
    private $patient_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^(0|1)$",
     *  title="state",
     *  description="1 if patient is currently logged in. 0 if logged out",
     *  type={"string","null"},
     *  enum={"waiting_for_injection","with_injection_staff","waiting_to_be_excused"},
     *  default="",
     * )
     *
     * @var string
     */
    private $state;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="clinic_id",
     *  description="Location at which patient logged in. Links to clinic_id.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $clinic_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  title="tray_location",
     *  description="Location of patients vials",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $tray_location;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="num_vials",
     *  description="Number of active bottles belonging to this patient.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $number_vials;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\s: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="login_note",
     *  description="Login notes relating to the patient. This is a non-HIPPA compliant notes field because user access to this data is not logged or controlled.",
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $login_note;
    
    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
        'excuseTime' => 'excuse_time',
        'loginTime' => 'login_time',
        'timeOut' => 'scheduled_departure',
        'timeLeft' => 'last_departure_attempt',
        'login_id' => 'encounter_id'
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'patient_id' => array('exists:patient,patient_id,archived,F'),
            'state' => array('in:waiting_for_injection,with_injection_staff,waiting_to_be_excused,logged_out'),
            'login_time' => array('date_format:Y-m-d H:i:s'),
            'excuse_time' => array('date_format:Y-m-d H:i:s'),
            'last_departure_attempt' => array('date_format:Y-m-d H:i:s'),
            'scheduled_departure' => array('date_format:Y-m-d H:i:s'),
            'clinic_id' => array('exists:clinic,clinic_id,deleted,F')
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id = null)
    {
        $Validator->sometimes(['patient_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}


/**
 * Class SwaggerEncounter
 *
 * @package App
 *
 * @SWG\Definition(
 *   definition="SwaggerEncounter",
 * )
 *
 */

class SwaggerEncounter
{
    /**
    * @SWG\Property(
    *  example="1",
    *  pattern="^(0|1)$",
    *  title="state",
    *  description="1 if patient is currently logged in. 0 if logged out",
    *  type={"string","null"},
    *  enum={"waiting_for_injection","with_injection_staff","waiting_to_be_excused"},
    *  default="",
    * )
    * @var string
    */
    private $state;
}
