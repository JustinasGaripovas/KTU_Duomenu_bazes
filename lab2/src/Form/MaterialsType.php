<?php

namespace App\Form;

use App\Entity\Materials;
use App\Entity\Warehouse;
use App\Entity\WinterJob;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Amount',null,[
                'label' => 'Kiekis',
                'required' => true,
            ])
            ->add('Material',ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Druska' => 'Salt',
                    'Smėlis' => 'Sand',
                    'Tirpalas' => 'Solution',
                ),
                'label' => 'Medžiaga',
            ))
            ->add('fk_winterjob', EntityType::class, array(
                'class' => WinterJob::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => "Žiemos darbas"
            ))
            ->add('fk_warehouse', EntityType::class, array(
                'class' => Warehouse::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => "Sandėlis darbas"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materials::class,
        ]);
    }
}
