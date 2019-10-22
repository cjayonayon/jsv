<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\EmployeeUser\ItemsRepository;

class CheckItemValidator extends ConstraintValidator
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

        preg_match('/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v\=[a-zA-Z0-9\/\*\-\_\?\;\%\=\.]/', $value, $output);

        if($output == null){
            return;
        }

        $link = $this->context->getRoot()->getData()->getVideoId();
            $temp = explode('?v=', $link);
            $links = $temp[1];
            $temp2 = explode('&list=', $links);
            $videoId = $temp2[0];

        $item = $this->ItemsRepository->findOneBy([
            'videoId' => $videoId,
            'itemGroup' => $this->context->getRoot()->getData()->getItemGroup()
        ]);

        if ($item == null) {
            return;
        }

        // dd( $item->checkDuplicateGroupItem($this->context->getRoot()->getData()->getItemGroup()) );
        if($item->checkDuplicateItem($this->context->getRoot()->getData()->getEmployee() )){
                /* @var $constraint \App\Validator\CheckItem */
            $this->context->buildViolation($constraint->checkDuplicateItem)
                ->atPath('videoId')                
                ->addViolation();
        }else if($item->checkDuplicateGroupItem($this->context->getRoot()->getData()->getItemGroup())){
            /* @var $constraint \App\Validator\CheckItem */
            $this->context->buildViolation($constraint->checkDuplicateGroupItem)
                ->atPath('videoId')                
                ->addViolation();
        }else if ( !$item->checkAddedItem($this->context->getRoot()->getData()->getItemGroup()) ){
                /* @var $constraint \App\Validator\CheckItem */
            $this->context->buildViolation($constraint->checkAddedItem)
                ->atPath('videoId')                
                ->addViolation();
        }
    }
}
