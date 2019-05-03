<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Artist;
use App\Entity\Search;
use App\Form\EventType;
use App\Form\SearchType;
use App\Form\EventSearchType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConcertController extends AbstractController
{
    /**
     * Méthode gérant l'affichage de tous les concerts en base
     * @Route("/", name="concerts")
     */
    public function show(EventRepository $eventRepository, EntityManagerInterface $em, Request $request)
    {
        // Requête dédiée QueryBuilder pour afficher par défaut les concerts des 3 prochains mois
        $concerts = $eventRepository->showEventsLastThreeMonths();

        $search = new Event();
        $form = $this->createForm(EventSearchType::class, $search);
        $form->handleRequest($request);       
        
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $request->query->all();
            $concerts = $eventRepository->findBy($filters);
        }

        return $this->render('concert/index.html.twig', [
            'concerts'=>$concerts,
            'form' => $form->createView(),
        ]);
    }


}
