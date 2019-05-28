<?php

namespace App\Form;

use App\Entity\RoadSection;
use App\Entity\WinterJob;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoadSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name',null,[
                'label' => 'Pavadinimas',
                'required' => true,
            ])
            ->add('Begin',IntegerType::class,[
                'label' => 'Ruožo pradžia',
                'required' => true,
            ])
            ->add('End',IntegerType::class,[
                'label' => 'Ruožo pabaiga',
                'required' => true,
            ])
            ->add('AverageWidth',IntegerType::class,[
                'label' => 'Vidutinis ruožo plotis',
                'required' => true,
            ])

            ->add('MaintenanceLevel', ChoiceType::class,[
                'label' => 'Priežiuros lygis',
                'choices' => array(
                    'Aukštas' => 2,
                    'Vidutinis' => 1,
                    'Žemas' => 0,
                ),
                'required' => true,
            ])

            ->add('Type', ChoiceType::class,[
                'label' => 'Ruožo tipas',
                'choices' => array(
                    'Automagistrale' => 'Automagistrale',
                    'Paprastas' => 'Paprastas',
                    'Rajoninis' => 'Rajoninis',
                ),
                'required' => true,
            ])

            ->add('fk_winterjob', EntityType::class, array(
                'class' => WinterJob::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => "Žiemos darbas"
            ))           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoadSection::class,
        ]);
    }
}
