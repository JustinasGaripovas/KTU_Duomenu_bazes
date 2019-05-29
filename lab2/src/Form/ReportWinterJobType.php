<?php

namespace App\Form;

use App\Entity\WinterJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportWinterJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', DateType::class, array(
                'widget' => 'single_text',
                'mapped' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                    'type' => 'date'
                ],
                'required' => true,
                'label' => 'Darbo pradžios data',

            ))
            ->add('to', DateType::class, array(
                'widget' => 'single_text',
                'mapped' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                    'type' => 'date'
                ],
                'required' => true,
                'label' => 'Darbo pabaigos data',

            ))
            ->add('Preview', SubmitType::class,array(
                'attr' => [
                    'class' => 'd-inline-block btn btn-success'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WinterJob::class,
        ]);
    }
}
