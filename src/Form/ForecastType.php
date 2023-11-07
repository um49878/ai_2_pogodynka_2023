<?php

namespace App\Form;

use App\Entity\Forecast;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'attr' => ['placeholder' => 'YYYY-MM-DD'],])
            ->add('temperature', NumberType::class)
            ->add('wind_direction')
            ->add('wind_speed', NumberType::class)
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => function ($location){
                    return $location->getCity() . ', ' . $location->getCountry();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecast::class,
        ]);
    }
}
