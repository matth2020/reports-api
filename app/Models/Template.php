<?php

namespace App\Models;

class Template extends BaseModel
{
    /**
     * Class Template.
     *
     *
     * @SWG\Definition(
     *   definition="Template",
     *   required={"name,extension,name"}
     * )
     */

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'template';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'template_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    protected $with = ['printers:name,printer.printer_id'];

    protected $hidden = ['queriesCSV'];

    /**
     * relationships
     */
    public function printers()
    {
        return $this->hasManyThrough('App\Models\Printer', 'App\Models\TemplatePrinter', 'template_id', 'printer_id', 'template_id', 'printer_id');
    }

    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'name' => ['standard', 'between:0,64','unique:template,name,'.$id.',template_id'],
            'extension' => ['in:pdf,prn']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['name','template','extension'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
