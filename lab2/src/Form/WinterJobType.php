<?php

namespace App\Form;

use App\Entity\WinterJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WinterJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('StartedAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                    'type' => 'date'
                ],
                'required' => true,
                'label' => 'Darbo pradžios data',

            ))
            ->add('FinishedAt', DateType::class, array(
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                    'type' => 'date'
                ],
                'required' => true,
                'label' => 'Darbo pabaigos data',

            ))
            ->add('EstimatedCost',IntegerType::class,[
                'label' => 'Numatoma kaina',
                'required' => true,
            ])
            ->add('ActualCost',IntegerType::class,[
                'label' => 'Reali kaina',
                'required' => true,
            ])
            ->add('Temperature',IntegerType::class,[
                'label' => 'Temperatūra',
                'required' => true,
            ])

            ->add('GeneralCondition',ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Normali' => 'Normal',
                    'Ledas' => 'Ice',
                    'Saulėta' => 'Sunny',
                ),
                'label' => 'Būklė',
            ))

            ->add('MoistureLevel',IntegerType::class,[
                'label' => 'Drėgmės lygis',
                'required' => true,
            ])
            ->add('PressureLevel',IntegerType::class,[
                'label' => 'Slėgio lygis',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WinterJob::class,
        ]);
    }
}
