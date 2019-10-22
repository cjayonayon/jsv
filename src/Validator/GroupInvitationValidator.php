<?php

namespace App\Validator;

use App\Repository\Admin\InvitationRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GroupInvitationValidator extends ConstraintValidator
{
    private $invitationRepository;

    public function __construct(InvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        
        $existingUser = $this->invitationRepository->findOneBy([
            'userGroup' => $value
            ]);
            
        if(!$existingUser){
            return;
        }

        if ($existingUser->getUserGroup()->getUser() != null) {
            /* @var $constraint \App\Validator\GroupInvitation */
            $this->context->buildViolation($constraint->groupRegisteredMessage)
                ->addViolation();
            return;
        }
    }
}
