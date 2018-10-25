<?php

namespace App\Form;

use App\Entity\FloodedRoads;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FloodedRoadsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('RoadId')
            ->add('RoadName')
            ->add('SectionBegin')
            ->add('SectionEnd')
            ->add('WaterDeep')
            ->add('Notes')
            ->add('Status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FloodedRoads::class,
            'translation_domain' => 'flooded_roads'
        ]);
    }
}
