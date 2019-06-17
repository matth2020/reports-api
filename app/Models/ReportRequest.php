<?php

namespace App\Models;

use App\Models\PrintJob;
use Illuminate\Validation\Rule;

/**
 * Class ReportRequest.
 *
 *
 * @SWG\Definition(
 *   definition="ReportRequest",
 *   required={"prints"}
 * )
 */
class ReportRequest extends BaseModel
{

    /**
     * Note this model does not exist in the database. It is for swagger
     * and validation purposes only
     */

    /**
     * @SWG\Property(
     *  title="prints",
     *  description="An array of print jobs to create",
     *  type="array",
     *  @SWG\Items(ref="#/definitions/PrintJob")
     * )
     *
     * @var int
     */
    private $prints;

    /**
     * @SWG\Property(
     *  example="1",
     *  pattern="^[0-9]+$",
     *  title="template_id",
     *  description="Id of the template to generate the report from.",
     *  minLength=0,
     *  maxLength=11,
     *  type={"integer","null"},
     *  default="",
     * )
     *
     * @var int
     */
    private $template_id;
    
    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'reports_id' => ['exists:reports,reports_id'],
            'template_id' => ['required','exists:template,template_id'],
            'treatment_set_id' => ['exists:treatment_set,treatment_set_id', Rule::requiredIf(function () use ($data) {
                $Template = Template::find($data['template_id']);
                $required = explode(',', $Template->queriesCSV);
                return !!array_search('treatmentset', $required);
            })],
            'purchase_order_id' => ['exists:purchase_order,purchase_order', Rule::requiredIf(function () use ($data) {
                $Template = Template::find($data['template_id']);
                $required = explode(',', $Template->queriesCSV);
                return !!array_search('purchaseorder', $required);
            })],
            'date' => ['date_format:Y-m-d'],
            'prints' => [
                'array',
                'required_with:reports_id',
                function ($attribute, $value, $fail) use ($data) {
                    $errors = [];
                    $PrintJob = new PrintJob();
                    foreach ($value as $idx => $printJob) {
                        $printJob['template_id'] = $data['template_id'];
                        if (!$PrintJob->validate($printJob, null)) {
                            $prop = $attribute.'_'.($idx+1);
                            $errors[$prop] = $PrintJob->errors();
                        }
                    }
                    if (sizeOf($errors) > 0) {
                        $fail([$attribute => $errors]);
                    }
                }
            ]
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
