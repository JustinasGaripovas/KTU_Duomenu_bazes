<?php

namespace App\Form;

use App\Entity\Mechanism;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MechanismType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Number')
            ->add('Type', ChoiceType::class, array(
                'choices' => $options['mechanism_choices']
            ))
            ->add('Subunit', ChoiceType::class, array(
                'choices' => $options['subunit_choices']
            ))
            ->add('TypeId', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mechanism_choices' => array(),
            'subunit_choices' => array(),
            'data_class' => Mechanism::class,
        ]);
    }
}
