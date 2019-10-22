<?php

namespace App\Form;

use App\Entity\Admin\Invitation;
use App\Entity\Admin\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

class InvitationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('userGroup', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'groupName',
                'placeholder' => 'Choose a Group',
                'choice_value' => 'id',
                'constraints' => [new NotBlank(['message'=>'Please select a group.'])]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invitation::class,
        ]);
    }
}
