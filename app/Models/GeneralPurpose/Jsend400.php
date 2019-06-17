<?php

namespace App\Models\GeneralPurpose;

/**
 * Class Jsend400.
 *
 *
 * @SWG\Definition(
 *   required={"status"}
 * )
 */
class Jsend400
{
    /**
     * @SWG\Property(
     *  example="validation"
     * )
     *
     * @var int
     */
    private $status;

    /**
     * @SWG\Property(
     *  example="[array of validation errors]"
     * )
     *
     * @var mixed
     */
    private $errors;
}
