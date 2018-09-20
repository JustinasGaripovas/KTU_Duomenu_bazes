<?php

namespace App\Form;

use App\Entity\WinterJobs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WinterJobsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Mechanism', ChoiceType::class, array(
                'choices' => $options['mechanism_choices']
            ))
            ->add('job', ChoiceType::class, array(
                'choices' => $options['jobs_choices']
            ))
            ->add('RoadSectionSearch')
            ->add('RoadSection', HiddenType::class)
            ->add('RoadName', HiddenType::class)
            ->add('maintenanceLevel', ChoiceType::class, array(
                'choices' => array(
                    '1' => 1,
                    '2' => 2,
                    '3' => 3
                )
            ))
            ->add('SectionBegin')
            ->add('SectionEnd')
            ->add('SaltChecked', CheckboxType::class, array(
                'required' => false
            ))
            ->add('SandChecked', CheckboxType::class, array(
                'required' => false
            ))
            ->add('MixChecked', CheckboxType::class, array(
                'required' => false
            ))
            ->add('SolutionChecked', CheckboxType::class, array(
                'required' => false
            ))
            ->add('Salt')
            ->add('Sand')
            ->add('Mix')
            ->add('Solution')
            ->add('TimeFrom')
            ->add('TimeTo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WinterJobs::class,
            'translation_domain' => 'translation',
            'mechanism_choices' => array(),
            'jobs_choices' => array()
        ]);
    }
}
