<?php

namespace App\Models\GeneralPurpose;

/**
 * Class SimpleInjection.
 */
class SimpleInjection
{
    /**
    * Date the injection is due or was given
    *
    * @var string
    */
    public $date;

    /**
    * Injection dose
    *
    * @var int
    */
    public $dose;

    /**
    * The type of injection (predicted, adjusted, etc)
    *
    * @var string
    */
    public $type;

    /**
     * The note attached to the injection
     *
     * @var string
     */
    public $note;

    /**
     * The dilution of the injection
     *
     * @var int
     */
    public $dilution;

    /**
     * The color associated with the injection
     *
     * @var string
     */
    public $color;
}
