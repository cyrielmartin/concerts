<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventSearchCityType;
use App\Form\EventSearchDateType;
use App\Form\EventSearchArtistType;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Mapping\OrderBy;

class ConcertController extends AbstractController 
{
    /**
     * Méthode gérant l'affichage de tous les concerts en base
     * @Route("/", name="concerts")
     */
    public function show(EventRepository $eventRepository, Request $request)
    {
        // Par défaut, requête QueryBuilder pour n'afficher QUE les concerts des 3 prochains mois
        $concerts = $eventRepository->showEventsLastThreeMonths();

        // Recherche par critère 
        $search = new Event();

        // Gestion formulaire recherche par artiste
        $formArtist = $this->createForm(EventSearchArtistType::class, $search);
        $formArtist->handleRequest($request);

        if ($formArtist->isSubmitted() && $formArtist->isValid()) {
            $filters = $request->query->all();
            $concerts = $eventRepository->findBy($filters, ['date' => 'ASC']);
        }

        // Gestion formulaire recherche par ville
        $formCity = $this->createForm(EventSearchCityType::class, $search);
        $formCity->handleRequest($request);

        if ($formCity->isSubmitted() && $formCity->isValid()) {
            $filters = $request->query->all();
            $concerts = $eventRepository->findBy($filters, ['date' => 'ASC']);
        }

        return $this->render('concert/index.html.twig', [
            'concerts' => $concerts,
            'formArtist' => $formArtist->createView(),
            'formCity' => $formCity->createView(),
        ]);
    }
}
