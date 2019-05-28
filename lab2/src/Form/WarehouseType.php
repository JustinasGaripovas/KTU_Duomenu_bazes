<?php

namespace App\Form;

use App\Entity\Subunit;
use App\Entity\Warehouse;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WarehouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Capacity',IntegerType::class,[
                'label' => 'Maksimali talpa',
                'required' => true,
            ])
            ->add('CurrentCapacity',IntegerType::class,[
                'label' => 'Dabartinė talpa',
                'required' => true,
            ])
            ->add('fk_subunit', EntityType::class, array(
                'class' => Subunit::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => "Tarnyba"
            ))        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Warehouse::class,
        ]);
    }
}
