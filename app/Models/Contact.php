<?php

namespace App\Models;

/**
 * Class Contact.
 *
 *
 * @SWG\Definition(
 *   required={"method", "content"},
 * )
 */
class Contact
{
    /**
    * @SWG\Property(
    *  example="A message from Xtract Solutions",
    *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
    *  title="subject",
    *  description="Subject (valid only for email)",
    *  minLength=0,
    *  maxLength=150,
    *  type={"string","null"},
    *  default="",
    * )
    *
    * @var string
    */
    private $subject;

    /**
     * @SWG\Property(
     *  example="At test message",
     *  pattern="^[a-zA-Z0-9-\.\/\\\\\t: ,!#?()&+{|}\~<=>@\[\]^_]*$",
     *  title="content",
     *  description="The message content",
     *  minLength=0,
     *  maxLength=1024,
     *  type={"string","null"},
     *  default="",
     * )
     *
     * @var string
     */
    private $content;

    /**
     * @SWG\Property(
     *  example="email",
     *  title="method",
     *  description="The method by which to contact the patient.",
     *  type={"string","null"},
     *  enum={"email","sms"},
     *  default="",
     * )
     *
     * @var string
     */
    private $method;

    /**
    * An array of fields that need to be converted from one name
    * in the database (array index) to another in the json object (value).
    */
    public static $DBtoRestConversion = array(
    );

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
                'subject' => array('standard', 'required_if:method,email,Email,EMAIL', 'between:0,255'),
                'method' => array('in:email,Email,EMAIL,sms,SMS'),
                'content' => array('standard', 'between:0,1024')
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
