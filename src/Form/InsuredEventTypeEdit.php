<?php

namespace App\Form;

use App\Entity\InsuredEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class InsuredEventTypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('PayoutDate',DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete'=>'off'
        ]
    ))
            ->add('PayoutAmount');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InsuredEvent::class,
            'translation_domain' => 'translation'
        ]);
    }
}
