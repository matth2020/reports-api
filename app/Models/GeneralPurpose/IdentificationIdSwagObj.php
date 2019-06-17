<?php
namespace App\SwaggerObjs;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Login
 *
 * @package App
 *
 * @SWG\Definition(
 *   definition="IdentificationIdSwagObj",
 *   required={"idcode","phone"}
 * )
 *
 */

class IdentificationIdSwagObj extends Model
{
    /**
    * @SWG\Property(
    *  example="1111",
    *  title="idcode",
    *  description="Patient idcode/pin.",
    *  type="string",
    *  default="",
    * )
    * @var int
    */
    private $idcode;

    /**
    * @SWG\Property(
    *  example="1234",
    *  title="phone",
    *  description="Last 5 digits of patient primary phone number",
    *  type="string",
    *  default="",
    * )
    * @var int
    */
    private $phone;
}
