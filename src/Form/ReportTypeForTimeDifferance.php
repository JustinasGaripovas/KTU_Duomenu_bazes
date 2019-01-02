<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ReportTypeForTimeDifferance extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('From', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'mapped'=> false,
                'attr' => [
                    'class' => 'js-datepicker-1',
                    'autocomplete'=>'off']
            ))
            ->add('FilterSubunit', null, array(
                'mapped'=> false,
                'required' => false
            ))
            ->add('FilterRoad', null, array(
                'mapped'=> false,
                'required' => false
            ))
            ->add('Preview', SubmitType::class,array(
                'attr' => [
                    'class' => 'd-inline-block btn btn-success'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped'=>false,
            'translation_domain' => 'translation'
        ]);
    }
}