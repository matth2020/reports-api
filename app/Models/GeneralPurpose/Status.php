<?php
namespace App\Models\GeneralPurpose;

/**
 * Class Status.
 *
 *
 * @SWG\Definition(
 *   required={"status"}
 * )
 */
class Status
{
    /**
     * @SWG\Property(
     *  example="7"
     * )
     *
     * @var int
     */
    private $status_id;

    /**
     * @SWG\Property(
     *  example="purchase_order"
     * )
     *
     * @var mixed
     */
    private $type;

    /**
     * @SWG\Property(
     *  example="complete"
     * )
     *
     * @var mixed
     */
    private $name;

    /**
     * @SWG\Property(
     *  example="1"
     * )
     *
     * @var mixed
     */
    private $position;
}
