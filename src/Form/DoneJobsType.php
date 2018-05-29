<?php

namespace App\Form;

use App\Entity\DoneJobs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class DoneJobsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('JobName')
            ->add('JobId', HiddenType::class)
            ->add('RoadSection')
            ->add('RoadSectionBegin')
            ->add('RoadSectionEnd')
            ->add('UnitOf')
            ->add('Quantity')
            ->add('Username', HiddenType::class, array(
                'disabled' => true,
                'required' => true
            ))
            ->add('DoneJobDate',  DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete'=>'off'
                    ]
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DoneJobs::class,
            'translation_domain' => 'translation'
        ]);
    }
}
