<?php

namespace App\Form;

use App\Entity\Inspection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InspectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('RoadIdd', null, array(
                'mapped' => false
            ))
            ->add('RoadId', HiddenType::class)
            ->add('RoadSectionBegin')
            ->add('RoadSectionEnd')
            ->add('isAdditional', CheckboxType::class, array(
                'required' => false
            ))
            ->add('roadCondition', ChoiceType::class, array(
                'choices' => array(
                    '      ' => Null,
                    'Šlapia' => 'Šlapia',
                    'Drėgna' => 'Drėgna',
                    'Vietomis drėgna' => 'Vietomis drėgna',
                    'Sausa' => 'Sausa',
                    ),
                'required'=>false,
            ))
            ->add('waveSize', ChoiceType::class,array(
                'choices' => array(
                    ''  => Null,
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ),
                'required'=>false,
            ))
            ->add('Place',null, array(
                'label' => 'Matavimo vieta (km.)'
            ))
            ->add('Note')
            ->add('RepairDate', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete'=>'off'],
                'required'=>true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Inspection::class,
            'translation_domain' => 'translation'
        ]);
    }
}
