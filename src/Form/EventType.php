<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Artist;
use App\Repository\CityRepository;
use App\Repository\ArtistRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'empty_data' => null,
                'invalid_message' => 'Veuillez sélectionner une date',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une date'
                    ]),
                ],
            ])
            ->add('artist', EntityType::class, [
                'label' => 'Artiste',
                'placeholder' => 'Sélectionner un artiste',
                'class' => Artist::class,
                'query_builder' => function (ArtistRepository $ar) {
                    return $ar->createQueryBuilder('a')
                    ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un artiste'
                    ]),
                ],
            ])
            ->add('city', EntityType::class, [
                'label' => 'Ville',
                'placeholder' => 'Sélectionner une ville',
                'class' => City::class,
                'query_builder' => function (CityRepository $cr) {
                    return $cr->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une ville'
                    ]),
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
