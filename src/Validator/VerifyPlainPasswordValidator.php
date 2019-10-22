<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\Admin\InvitationRepository;

class VerifyPlainPasswordValidator extends ConstraintValidator
{
    private $InvitationRepository;

    public function __construct(InvitationRepository $InvitationRepository)
    {
        $this->InvitationRepository = $InvitationRepository;
    }

    public function validate($value, Constraint $constraint)
    {   
        if($value == null){
            return;
        }

        $userEntity = $this->InvitationRepository->findOneBy([
            'password' => $value
        ]);
        
        if($userEntity == null){
            /* @var $constraint \App\Validator\VerifyPlainPassword */
            $this->context->buildViolation($constraint->tempPasswordMessage)
                 ->addViolation();
            return;
        }

    }
}
