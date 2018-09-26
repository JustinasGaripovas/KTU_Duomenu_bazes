<?php

namespace App\Form;

use App\Entity\InsuredEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class InsuredEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Subunit')
            ->add('RoadSectionSearch', null)
            ->add('RoadId', HiddenType::class,array(
                'required' => true
            ))
            ->add('RoadName', HiddenType::class,array(
                'required' => true
            ))
            ->add('SectionBegin', null, array(
                'required' => true
            ))
            ->add('SectionEnd', null, array(
                'required' => true
            ))
            ->add('DamagedStuff', null, array(
                'required' => true
            ))
            ->add('IsInsuredType',ChoiceType::class, array(
                'required' => true,
                'expanded' => true,
                'choices' => array(
                    'Draudiminis' => 1,
                    'Nedraudiminis' => 0,
                ),
                'label' => 'Ar tai draudiminis įvykis',
            ))
            ->add('Documents', null)
            ->add('EstimateToCompany', null)
            ->add('InsurensCompany', null)
            ->add('NumberOfDamage', null)
            ->add('DamageData', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off'
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InsuredEvent::class,
            'translation_domain' => 'translation'
        ]);
    }
}
