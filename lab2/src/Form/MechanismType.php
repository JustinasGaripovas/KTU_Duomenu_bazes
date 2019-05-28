<?php

namespace App\Form;

use App\Entity\Mechanism;
use App\Entity\WinterJob;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MechanismType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('VehicleCode',null,[
                'label' => 'Transporto priemonės nr',
            ])
            ->add('LastChecked', DateType::class, array(
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                    'type' => 'date'
                ],
                'required' => true,
                'label' => 'Paskutini kartą tikrinta',

            ))
            ->add('isUsable', ChoiceType::class,[
                'label' => 'Ar galima naudoti ?',
                'choices' => array(
                    'Taip' => 1,
                    'Ne' => 0,
                ),
                'required' => true,
            ])
            ->add('VehicleType',ChoiceType::class, array(
                'required' => true,
                'choices' => array(
                    'Grader' => 'Grader',
                    'Truck' => 'Truck',
                    'Cleaner' => 'Cleaner',
                ),
                'label' => 'Tipas',
            ))
            ->add('fk_winterjob', EntityType::class, array(
                'class' => WinterJob::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => "Žiemos darbas"
            ))        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mechanism::class,
        ]);
    }
}
