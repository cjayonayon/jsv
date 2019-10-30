<?php

namespace App\Form;

use App\Entity\Sysadmin\Employee;
use App\Entity\Sysadmin\Payroll;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class PayrollType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $this->userEmployees = $options['user_employees'];

        $builder
            ->add('amount', NumberType::class,[
                'label' => 'Salary Amount (₱)',
            ])
            ->add('paymentDate', DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'yyyy/mm/dd'
                ],
                'html5' => false,
                'label' => 'Date of Payment',
            ])
            ->add('startCoverage', DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'yyyy/mm/dd'
                ],
                'html5' => false,
                'label' => 'Start date of payment coverage',
            ])
            ->add('endCoverage', DateType::class,[
                'widget' => 'single_text',
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'yyyy/mm/dd'
                ],
                'html5' => false,
                'label' => 'End date of payment coverage',
            ])
            ->add('employeePayroll', EntityType::class,[
                'label' => 'Select employee to pay',
                'class' => Employee::class,
                'choices' => $this->userEmployees,
                'choice_label' => 'fullName',
                'placeholder' => 'Choose a Employee',
                'choice_value' => 'id'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payroll::class,
            'user_employees' => null
        ]);

    }
}
