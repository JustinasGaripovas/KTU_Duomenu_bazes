<?php

namespace App\Form;

use App\Entity\WinterJobRoadSection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WinterJobsRoadSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('RoadSectionSearch',null,array(
                'required' => true,
            ))
            ->add('SectionId',HiddenType::class)
            ->add('SectionName', HiddenType::class)
            ->add('SectionType', HiddenType::class)
            ->add('SectionBegin',null,array(
                'required' => true,
            ))
            ->add('SectionEnd',null,array(
                'required' => true,
            ))
            /*
            ->add('level', ChoiceType::class, array(
                'choices' => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3
                )
            ))*/
            ->add('level', HiddenType::class)
            ->add('SaltChecked', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('SandChecked', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('SolutionChecked', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('SaltValue',null,array(
                'required' => false,
            ))
            ->add('SandValue',null,array(
                'required' => false,
            ))
            ->add('SolutionValue',null,array(
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WinterJobRoadSection::class,
        ]);
    }
}
