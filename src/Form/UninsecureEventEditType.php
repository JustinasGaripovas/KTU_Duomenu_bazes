<?php

namespace App\Form;

use App\Entity\InsuredEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class UninsecureEventEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Subunit')
            ->add('RoadSearch', null, array(
                'mapped' => false
            ))
            ->add('RoadId', HiddenType::class)
            ->add('RoadName', HiddenType::class)
            ->add('SectionBegin')
            ->add('SectionEnd')
            ->add('DamagedStuff')
            ->add('DamageData',DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete'=>'off'
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InsuredEvent::class,
            'translation_domain' => 'translation'
        ]);
    }
}
