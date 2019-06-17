<?php
namespace App\SwaggerObjs;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Login
 *
 * @package App
 *
 * @SWG\Definition(
 *   definition="FMDSwagObj",
 *   required={"patient_id","state"}
 * )
 *
 */

class FMDSwagObj extends Model
{
    /**
    * @SWG\Property(
    *  example="",
    *  title="fmd",
    *  description="FMD to validate or identify.",
    *  type="string",
    *  default="",
    * )
    * )
    * @var int
    */
    private $fmd;
}
