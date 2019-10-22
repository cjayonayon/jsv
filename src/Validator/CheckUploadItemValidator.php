<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\EmployeeUser\ItemsRepository;

class CheckUploadItemValidator extends ConstraintValidator
{
    private $ItemsRepository;

    public function __construct(ItemsRepository $ItemsRepository)
    {
        $this->ItemsRepository = $ItemsRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        // dd($value);
        if($value == null){
            return;
        }
            
        $filename = $this->context->getRoot()->get('uploadFilename')->getData()->getClientOriginalName();

        $uploadItem = $this->ItemsRepository->findOneBy([
            'videoId' => $filename,
            'itemGroup' => $this->context->getRoot()->getData()->getItemGroup()
        ]);

        if ($uploadItem == null) {
            return;
        }
    // dd($uploadItem->checkAddedItem($this->context->getRoot()->getData()->getItemGroup()));

        if($uploadItem->checkDuplicateItem($this->context->getRoot()->getData()->getEmployee() )){
            /* @var $constraint \App\Validator\CheckUploadItem */
        $this->context->buildViolation($constraint->checkDuplicateItem)
            ->addViolation();

        }else if($uploadItem->checkDuplicateGroupItem($this->context->getRoot()->getData()->getItemGroup())){
            /* @var $constraint \App\Validator\CheckItem */
            $this->context->buildViolation($constraint->checkDuplicateGroupItem)
                ->atPath('videoId')                
                ->addViolation();
        }else if (!$uploadItem->checkAddedItem($this->context->getRoot()->getData()->getItemGroup() )){
                /* @var $constraint \App\Validator\CheckUploadItem */
            $this->context->buildViolation($constraint->checkAddedItem)
                ->addViolation();
        }
        
    }
}
