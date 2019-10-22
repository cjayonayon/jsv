<?php

namespace App\Validator;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MinDateValidator
{
    public function validate($object, ExecutionContextInterface $context)
    {
        $start = $context->getRoot()->getData()->getStartCoverage();
        $end = $object;
        
        if (is_a($start, \DateTime::class) && is_a($end, \DateTime::class)) {
            if ($end->format('U') - $start->format('U') < 0) {
                $context
                ->buildViolation('End Coverage must be greater than Start Coverage')
                ->addViolation();
            }
        }
    }
}
