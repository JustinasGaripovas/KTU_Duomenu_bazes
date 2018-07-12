<?php

namespace App\Form;

use App\Entity\RoadSection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoadSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('SectionId')
            ->add('unitId',ChoiceType::class, array(
                'choices' => array(
                    'Utenos' => 1,
                    'Molėtų' => 2,
                    'Zarasų' => 3,
                    'Ignalinos' => 4,
                    'Anykščių'=> 5,
                    'Mažeikių'=> 6,
                    'Plungės'=> 7,
                    'Telšių' => 9,
                    'Klaipėdos'=>11,
                    'Kretingos'	=>12,
                    'Skuodo'=>13,
                    'Šilutės'=>14,
                    'Alytaus'=>15,
                    'Lazdijų'=>16,
                    'Varėnos'=>	17,
                    'Jonavos'=>18,
                    'Kaišiadorių'=>19,
                    'Kauno'=>20,
                    'Kėdainių'=>21,
                    'Prienų'=>22,
                    'Raseinių'=>23,
                    'Marijampolės'=>24,
                    'Šakių'=>25,
                    'Vilkaviškio'=>26,
                    'Biržų'=>27,
                    'Kupiškio'=>28,
                    'Panevėžio'=>30,
                    'Pasvalio'=>31,
                    'Rokiškio'=>32,
                    'Akmenės'=>33,
                    'Joniškio'=>34,
                    'Kelmės'=>35,
                    'Pakruojo'=>36,
                    'Radviliškio'=>37,
                    'Šiaulių'=>38,
                    'Šalčininkų'=>39,
                    'Švenčionių'=>40,
                    'Trakų'=>41,
                    'Ukmergės'=>42,
                    'Vilniaus'=>43,
                    'Raseinių'=>44,
                    'Širvintų'=>45,
                    'Vievio'=>46,
                    'Jurbarko'=>47,
                    'Šilalės'=>48,
                    'Tauragės'=>49,
                )

            ))
            ->add('sectionName')
            ->add('sectionBegin')
            ->add('sectionEnd');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoadSection::class,
            'translation_domain' => 'translation'
        ]);
    }
}
