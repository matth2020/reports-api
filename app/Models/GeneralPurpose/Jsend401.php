<?php

namespace App\Models\GeneralPurpose;

/**
 * Class Jsend401.
 *
 *
 * @SWG\Definition(
 *   required={"status"}
 * )
 */
class Jsend401
{
    /**
     * @SWG\Property(
     *  example="fail"
     * )
     *
     * @var int
     */
    private $status;

    /**
     * @SWG\Property(
     *  example="Unauthorized"
     * )
     *
     * @var string
     */
    private $message;
}
