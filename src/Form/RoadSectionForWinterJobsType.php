<?php

namespace App\Form;

use App\Entity\RoadSectionForWinterJobs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoadSectionForWinterJobsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subunit', ChoiceType::class, array(
                'choices' => $options['subunit_choices']
            ))
            ->add('SectionId')
            ->add('sectionBegin')
            ->add('sectionEnd')
            ->add('sectionType', ChoiceType::class, array(
                'choices' => array(
                    'Magistralinis' => "Magistralinis",
                    'Krašto' => 'Krašto',
                    'Rajoninis' => 'Rajoninis',
                )));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoadSectionForWinterJobs::class,
            'subunit_choices' => array(),
            'translation_domain' => 'translation'
        ]);
    }
}
