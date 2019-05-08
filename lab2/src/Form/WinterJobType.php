<?php

namespace App\Form;

use App\Entity\WinterJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WinterJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('CreatedAt')
            ->add('FinishedAt')
            ->add('EstimatedCost')
            ->add('ActualCost')
            ->add('Temperature')
            ->add('GeneralCondition')
            ->add('MoistureLevel')
            ->add('PressureLevel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WinterJob::class,
        ]);
    }
}
