<?php

namespace App\Form;

use App\Entity\RoadSection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoadSectionForWinterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('SectionId')
            ->add('SectionName')
            ->add('SectionBegin')
            ->add('SectionEnd')
            ->add('level')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoadSection::class,
        ]);
    }
}
