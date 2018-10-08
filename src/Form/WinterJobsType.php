<?php

namespace App\Form;

use App\Entity\WinterJobs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('RoadSections', CollectionType::class, [
                'entry_type' => WinterJobsRoadSectionType::class,
                'label' => false,
                'entry_options' => [
                    'label' => false
                ],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('Date', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off'
                ]
            ))
            ->add('TimeFrom')
            ->add('TimeTo')
            ->add('save', SubmitType::class, array(
                'attr' => [
                    'class' => 'btn btn-success btn-block btn-lg'
                    ]
            ))
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
