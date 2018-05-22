<?php

namespace App\Form;

use App\Entity\LdapUser;
use App\Entity\Subunit;
use App\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class LdapUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('unit', EntityType::class, array(
                'class' => Unit::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.Name', 'ASC');
                },
                'choice_label' => 'Name',
            ))
            ->add('subunit', EntityType::class, array(
                'class' => Subunit::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.Name', 'ASC');
                },
                'choice_label' => 'Name',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LdapUser::class,
        ]);
    }
}
