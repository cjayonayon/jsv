<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MaxDateValidator
{
    public function validate($object, ExecutionContextInterface $context)
    {
        $start = $object;
        $end = $context->getRoot()->getData()->getEndCoverage();
        // dd($end);
        if (is_a($start, \DateTime::class) && is_a($end, \DateTime::class)) {
            if ($start->format('U') - $end->format('U') > 0) {
                $context
                ->buildViolation('Start Coverage must be less than End Coverage')
                ->addViolation();
            }
        }
    }
}
