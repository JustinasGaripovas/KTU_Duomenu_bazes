<?php

namespace App\Form;

use App\Entity\WinterJobRoads;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WinterJobRoadsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('SectionId')
            ->add('SectionName')
            ->add('SectionBegin')
            ->add('SectionEnd')
            ->add('FullSectionName')
            ->add('winterJobs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WinterJobRoads::class,
        ]);
    }
}
