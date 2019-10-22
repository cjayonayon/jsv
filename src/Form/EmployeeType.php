<?php

namespace App\Form;

use App\Entity\Sysadmin\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employeeId')
            ->add('email')
            ->add('lastName')
            ->add('firstName')
            ->add('middleName')
            ->add('birthDate', DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'yyyy/mm/dd'
                ],
                'html5' => false,
            ])
            ->add('address')
            ->add('telNumber')
            ->add('gender', ChoiceType::class,[
                'choices' => [
                    ' ' => null,
                    'Male' => 'Male',
                    'Female' => 'Female',
                ],
            ])
            ->add('dateEmployed', DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'yyyy/mm/dd'
                ],
                'html5' => false,
             ]) 
            ->add('salary', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
