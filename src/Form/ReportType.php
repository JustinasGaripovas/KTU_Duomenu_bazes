<?php

namespace App\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('From', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'mapped'=> false,
                'attr' => ['class' => 'js-datepicker-1']
            ))
            ->add('To', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'mapped'=> false,
                'attr' => ['class' => 'js-datepicker-2']
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped'=>false
        ]);
    }
}