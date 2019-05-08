<?php

namespace App\Form;

use App\Entity\Mechanism;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MechanismType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('VehicleCode')
            ->add('LastChecked')
            ->add('isUsable')
            ->add('VehicleType')
            ->add('fk_winterjob')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mechanism::class,
        ]);
    }
}
