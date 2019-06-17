<?php

namespace App\Models;

/**
 * Class QuestionnaireAssignment.
 *
 *
 * @SWG\Definition(
 *   definition="QuestionnaireAssignment",
 *   required={"group"}
 * )
 */

class QuestionnaireAssignment
{
    /**
    * @SWG\Property(
    *  example="test",
    *  title="groups",
    *  description="An array of one or more groups as returned by config where section = boxNames",
    *  @SWG\Schema(
    *    type={"string","array"},
    *  ),
    *  default="",
    * )
    * @var string
    */
    private $groups;
}
