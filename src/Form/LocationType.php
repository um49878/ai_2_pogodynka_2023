<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', null, ['attr' => ['placeholder' => 'Wprowadź nazwę lokalizacji'],])
            ->add('country', ChoiceType::class, [
                    'choices' => [
                        'Polska' => 'PL',
                        'Niemcy' => 'DE',
                        'Francja' => 'FR',
                        'Włochy' => 'IT',
                        'Wielka Brytania' => 'GB',
                        'Stany Zjednoczone Ameryki' => 'US',
                        'Bangladesz' => 'BD',
                    ],
                ])
            ->add('latitude', null, ['attr' => ['placeholder' => '##,#######'],])
            ->add('longitude', null, ['attr' => ['placeholder' => '##,#######'],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
