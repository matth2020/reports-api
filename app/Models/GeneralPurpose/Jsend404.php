<?php

namespace App\Models\GeneralPurpose;

/**
 * Class Jsend404.
 *
 *
 * @SWG\Definition(
 *   required={"status"}
 * )
 */
class Jsend404
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
     *  example="Resource could not be located"
     * )
     *
     * @var string
     */
    private $message;
}
