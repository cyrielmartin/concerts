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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EventSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add('date', DateType::class, [
        //     'label' => false,
        //     'widget' => 'single_text',
        //     'required' => false,
        //     'empty_data' => null,
        // ])
            ->add('artist', EntityType::class, [
                'label' => false,
                'placeholder' => 'Rechercher par artiste',
                'class' => Artist::class,
                'query_builder' => function (ArtistRepository $ar) {
                    return $ar->createQueryBuilder('a')
                    ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name'
            ])
            ->add('city', EntityType::class, [
                'label' => false,
                'placeholder' => 'Rechercher par ville',
                'class' => City::class,
                'query_builder' => function (CityRepository $cr) {
                    return $cr->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name'

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
