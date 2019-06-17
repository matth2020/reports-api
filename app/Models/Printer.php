<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Printer.
 *
 *
 * @SWG\Definition(
 *   definition="Printer",
 * )
 */
class Printer extends Model
{
    use SoftDeletes;
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'printer';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'printer_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    protected $hidden = ['deleted_at'];

    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'external_id' => ['integer','unique:printer,external_id,'.$id.',printer_id'],
            'name' => ['standard', 'between:0,64','unique:printer,name,'.$id.',printer_id']
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['external_id', 'name'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
