<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class UserInvitation extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Email does not exist';
    public $userRegisteredMessage = 'Email has already registered and has been assigned to a group.';
}
