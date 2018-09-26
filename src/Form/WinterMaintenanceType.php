<?php

namespace App\Form;

use App\Entity\WinterMaintenance;
use App\Repository\ChoicesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class WinterMaintenanceType extends AbstractType
{
    private $rep;
    private $winter;
    private $index = 0;

    public function __construct(ChoicesRepository $choicesRepository)
    {
        $this->rep = $choicesRepository;
    }

    public function createArrayWithArrayKeys ($choiceId){
        $choicesName = array();
        $choicesKey = array();
        $choices = $this->rep->findChoiceByChoiceId($choiceId);
        foreach ($choices as $item) {
          array_push($choicesName, $item['Choice']);
          array_push($choicesKey, $item['Choice']);
        }
        return array_combine($choicesKey, $choicesName);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('M1', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('M2', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('M3', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('K1', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('K2', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('K3', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('R1', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('R2', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('R3', CheckboxType::class, array(
                'mapped' =>false,
                'required' => false
            ))
            ->add('reportFor', ChoiceType::class, array(
                'choices' => array(
                    '6:00 - 5:45' => '6:00',
                    '9:00 - 8:45' => '9:00',
                    '13:00 - 12:45' => '13:00',
                    '16:00 - 15:45' => '16:00'
                )
            ))
            ->add('Subunit', HiddenType::class)
            ->add('RoadConditionHighway', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionHighway2', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionHighway3', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionLocal', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionLocal2', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionLocal3', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionDistrict', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionDistrict2', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('RoadConditionDistrict3', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(1),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('Weather', ChoiceType::class, array(
                'choices' => $this->createArrayWithArrayKeys(2),
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-inline',
                ]
            ))
            ->add('TrafficChanges', ChoiceType::Class, array(
                'choices' => array(
                    'Gerėja' => 'Gerėja',
                    'Blogėja' => 'Blogėja',
                    'Nepasikeitė' => 'Nepasikeitė'
                )
            ))
            ->add('BlockedRoads')
            ->add('OtherEvents')
            ->add('Mechanism')
            ->add('RoadConditionScore', ChoiceType::class, array(
                'choices' =>array(
                    '1 - Geros eismo sąlygos visuose keliuose (keliai sausi, drėgni)' => 1,
                    '2 - Geros eismo sąlygos pagrindiniuose keliuose, rajoniniuose yra slidžių ruožų' => 2,
                    '3 - Yra slidžių ruožų ne tik rajoniniuose, bet ir pagrindiniuose keliuose' => 3,
                    '4 -Sudėtingos eismo sąlygos visuose keliuose. (keliai padengti sniego sluoksniu, susiformavęs plikledis)' => 4,
                    '5- Labai sudėtingos eismo sąlygos, yra neišvažiuojamų kelių. (staigi stipri lijundra, gausiai prisnigta)' => 5
                ),
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-block',
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WinterMaintenance::class,
            'translation_domain' => 'translation'
        ]);
    }
}
