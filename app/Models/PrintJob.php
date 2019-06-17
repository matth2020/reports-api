<?php

namespace App\Models;

/**
 * Class PrintJob.
 *
 *
 * @SWG\Definition(
 *   definition="PrintJob",
 *   required={"printer_id"}
 * )
 */
class PrintJob extends BaseModel
{

    /**
     * Note this model does not exist in the database. It is for swagger
     * and validation purposes only
     */

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="printer_id",
     *  description="Id of the printer to use.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $printer_id;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="copies",
     *  description="Number of copies to create.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $copies;
    
    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            // should validate printer_id is in the template_printer table...
            'printer_id' => ['exists:printer,printer_id','exists:template_printer,printer_id,template_id,'.$data['template_id']],
            'copies' => 'in:1,2,3,4,5,6,7,8,9'
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
