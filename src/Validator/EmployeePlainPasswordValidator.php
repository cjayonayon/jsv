<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\EmployeeUser\EmployeeInvitationRepository;

class EmployeePlainPasswordValidator extends ConstraintValidator
{
    private $EmployeeInvitationRepository;

    public function __construct(EmployeeInvitationRepository $EmployeeInvitationRepository)
    {
        $this->EmployeeInvitationRepository = $EmployeeInvitationRepository;
    }

    public function validate($value, Constraint $constraint)
    {   
        if($value == null){
            return;
        }

        $userEntity = $this->EmployeeInvitationRepository->findOneBy([
            'password' => $value,
            'employee' => $this->context->getRoot()->getData()->getEmployeeId()
        ]);
        
        if($userEntity == null){
            /* @var $constraint \App\Validator\EmployeePlainPassword */
            $this->context->buildViolation($constraint->tempPasswordMessage)
                 ->addViolation();
            return;
        }
    }
}
