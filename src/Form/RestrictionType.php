<?php

namespace App\Form;

use App\Entity\Restriction;
use App\Entity\RestrictionStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class RestrictionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('RoadIdd', null,array(
                'mapped' => false
            ))
            ->add('RoadId', HiddenType::class)
            ->add('RoadName')
            ->add('subunit')
            ->add('SectionBegin')
            ->add('SectionEnd')
            ->add('Place')
            ->add('Jobs')
            ->add('Restrictions')
            ->add('DateFrom', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete'=>'off'],)
            )
            ->add('DateTo', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete'=>'off'],)
            )
            ->add('Notes')
            ->add('Contractor')
            ->add('Status', EntityType::class, array(
                'class' => RestrictionStatus::class,
        'choice_label' => 'StatusName',
    ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restriction::class,
            'translation_domain' => 'translation'
        ]);
    }
}
