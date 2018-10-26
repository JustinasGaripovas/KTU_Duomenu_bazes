<?php

namespace App\Form;

use App\Entity\LdapUser;
use App\Entity\Subunit;
use App\Entity\Unit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use function Symfony\Component\Validator\Tests\Constraints\choice_callback;

class LdapUserType extends AbstractType
{
    const admin        = 'ADMIN';
    const super_viewer = 'SUPER_VIEWER';
    const unit_viewer  = 'UNIT_VIEWER';
    const super_master = 'SUPER_MASTER';
    const road_master  = 'ROAD_MASTER';
    const worker       = 'WORKER';

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
            ->add('role',ChoiceType::class,array(
                'choices' => array(
                    'Administratorius' => self::admin,
                    'SUPER VIEWER' => self::super_viewer,
                    'UNIT VIEWER' => self::unit_viewer,
                    'SUPER_Master' => self::super_master,
                    'ROAD MASTER' => self::road_master,
                    'Darbuotojas' => self::worker
                )
            ))
            ->add('inspection',ChoiceType::class, array(
                'choices' => array(
                    'Nemato' => 0,
                    'Mato' => 1,
                    'Mato ir redaguoja' => 2,
                    'Mato, redaguoja ir trina' => 3
                )
            ))
            ->add('doneJobs',ChoiceType::class, array(
                'choices' => array(
                    'Nemato' => 0,
                    'Mato' => 1,
                    'Mato ir redaguoja' => 2,
                    'Mato, redaguoja ir trina' => 3
                )
            ))
            ->add('restrictions',ChoiceType::class, array(
                'choices' => array(
                    'Nemato' => 0,
                    'Mato' => 1,
                    'Mato ir redaguoja' => 2,
                    'Mato, redaguoja ir trina' => 3
                )
            ))
            ->add('winter',ChoiceType::class, array(
                'choices' => array(
                    'Nemato' => 0,
                    'Mato' => 1,
                    'Mato ir redaguoja' => 2,
                    'Mato, redaguoja ir trina' => 3
                )
            ))
            ->add('insuredEvent',ChoiceType::class, array(
                'choices' => array(
                    'Nemato' => 0,
                    'Mato' => 1,
                    'Mato ir redaguoja' => 2,
                    'Mato, redaguoja ir trina' => 3
                )
            ))
            ->add('reports',ChoiceType::class, array(
                'choices' => array(
                    'Nemato' => 0,
                    'Mato' => 1,
                    'Mato ir redaguoja' => 2,
                    'Mato, redaguoja ir trina' => 3
                )
            ))
            ->add('flood',ChoiceType::class, array(
                'choices' => array(
                    'Nemato' => 0,
                    'Mato' => 1,
                    'Mato ir redaguoja' => 2,
                    'Mato, redaguoja ir trina' => 3
                )
            ))

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LdapUser::class,
            'translation_domain' => 'translation'
        ]);
    }
}
