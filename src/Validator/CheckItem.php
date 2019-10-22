<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class CheckItem extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $checkDuplicateItem = 'Item has been already added to queue.';
    public $checkAddedItem = 'A removed Item can only be repeated once every 2 hours.';
    public $checkDuplicateGroupItem = 'Item has already been added to queue by another user.';
}
