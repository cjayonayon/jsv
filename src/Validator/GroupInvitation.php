<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class GroupInvitation extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $groupRegisteredMessage = 'Group has been selected please select a new group.';
}
