<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Formulaire permettant l'affichage des concerts par artiste

class EventSearchArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', EntityType::class, [
                'label' => false,
                'placeholder' => 'Artistes',
                'class' => Artist::class,
                'query_builder' => function (ArtistRepository $cr) {
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
