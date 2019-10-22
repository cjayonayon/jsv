<?php

namespace App\Validator;

use App\Repository\Admin\InvitationRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserInvitationValidator extends ConstraintValidator
{
    private $invitationRepository;

    public function __construct(InvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        
        $existingUser = $this->invitationRepository->findOneBy([
            'email' => $value
            ]);
            
        if(!$existingUser){
            return;
        }
        
        if ($existingUser->getStatus() == "Accepted") {
            /* @var $constraint \App\Validator\UserInvitation */
            $this->context->buildViolation($constraint->userRegisteredMessage)
                ->addViolation();
            return;
        }

    }
}
