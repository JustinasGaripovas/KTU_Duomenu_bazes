<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ReportType extends AbstractType
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
            ->add('To', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'mapped'=> false,
                'attr' => [
                    'class' => 'js-datepicker-2',
                    'autocomplete'=>'off']
            ))
            ->add('Preview', SubmitType::class,array(
                'attr' => [
                    'class' => 'd-inline-block btn btn-success'
                ]
            ))
            ->add('GeneratePDF', SubmitType::class,array(
                'attr' => [
                    'class' => 'd-inline-block btn btn-success'
                ]
            ))
            ->add('GenerateXLS', SubmitType::class,array(
                'attr' => [
                    'class' => 'd-inline btn btn-success'
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