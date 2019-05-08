<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\WinterJob;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Description',null,[
                'label' => 'Aprašas',
                'required' => true,
            ])
            ->add('Quadrature',null,[
                'label' => 'Kvadratūra',
                'required' => true,
            ])
            ->add('QuadratureUnit',null,[
                'label' => 'Kvadratūros matas',
                'required' => true,
            ])
            ->add('Code',null,[
                'label' => 'Kodas',
                'required' => true,
            ])
            ->add('DangerLevel',ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Aukštas' => 2,
                    'Vidutinis' => 1,
                    'Žemas' => 0,
                    'Nėra' => -1,
                ),
                'label' => 'Pavojaus lygis',
            ))
            ->add('fk_winterjob', EntityType::class, array(
                'class' => WinterJob::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => "Žiemos darbas"
            ))
        ;

        dump(function (EntityRepository $er) {
            return $er->createQueryBuilder('u')
                ->orderBy('u.id', 'ASC');
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
