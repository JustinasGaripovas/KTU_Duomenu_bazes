<?php

namespace App\Form;

use App\Entity\RoadSection;
use App\Entity\Subunit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoadSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('SectionId')
            ->add('unitId',EntityType::class, array(
                'class' => Subunit::class,
                'choice_label' => 'Name',
            ))
            ->add('sectionName')
            ->add('sectionBegin')
            ->add('sectionEnd');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoadSection::class,
            'translation_domain' => 'translation'
        ]);
    }
}
