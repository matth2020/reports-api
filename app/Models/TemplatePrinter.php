<?php

namespace App\Models;

class TemplatePrinter extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'template_printer';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = ['printer_id','template_id'];

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'printer_id' => ['integer', 'exists:printer,printer_id'],
            'template_id' => ['integer', 'exists:template,template_id']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['printer_id','template_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
