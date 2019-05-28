<?php

namespace App\Form;

use App\Entity\Subunit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubunitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name',null,[
                'label' => 'Pavadinimas',
                'required' => true,
            ])
            ->add('Address',null,[
                'label' => 'Adresas',
                'required' => true,
            ])
            ->add('Phone',null,[
                'label' => 'Telefonas',
                'required' => true,
            ])
            ->add('Email',EmailType::class,[
                'label' => 'Paštas',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subunit::class,
        ]);
    }
}
