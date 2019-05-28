<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\Subunit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', null, [
                'label' => 'Vardas',
                'required' => true
            ])
            ->add('Surname', null, [
                'label' => 'Pavardė',
                'required' => true
            ])
            ->add('Phone', null, [
                'label' => 'Telefonas',
                'required' => true
            ])
            ->add('Address', null, [
                'label' => 'Adresas',
                'required' => true
            ])
            ->add('Email', null, [
                'label' => 'Paštas',
                'required' => true
            ])
            ->add('Role',ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Administratorius' => 'ADMIN',
                    'Darbuotojas' => 'WORKER',
                    'Manedžeris' => 'MANAGER',
                    'Kokybės kontrolės darbuotojas' => 'QUALITY_CONTROLER',
                ),
                'label' => 'Rolė',
            ))

            ->add('fk_subunit', EntityType::class, array(
                'class' => Subunit::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => "Tarnyba"
            ))
            ->add('WinterJobs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
