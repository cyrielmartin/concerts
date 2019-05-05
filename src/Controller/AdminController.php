<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class gérant l'admin des concerts
 * @Route("/concert/admin", name="concert_admin_")
 */

class AdminController extends AbstractController
{
    /**
     * Méthode gérant l'affichage de tous les concerts en base
     * @Route("/", name="show")
     */
    public function show(EventRepository $eventRepository)
    {
        // Requête dédiée QueryBuilder pour afficher les concerts par ordre chronologique
        $concerts = $eventRepository->showEventOrderDateAsc();

        return $this->render('admin/index.html.twig', [
            'concerts' => $concerts,
        ]);
    }

    /**
     * Méthode gérant l'ajout d'un nouveau concert
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        $concert = new Event();
        $form = $this->createForm(EventType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $concert = $form->getData();
            $em->persist($concert);
            $em->flush();

            // Génération d'un flash message à l'ajout d'un concert
            $this->addFlash(
                'success',
                'Le concert a bien été ajouté !'
            );

            return $this->redirectToRoute('concert_admin_show');
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
            'tab_type' => 'Ajouter',
        ]);
    }

    /**
     * Méthode gérant la modification d'un concert existant
     * @Route("/edit/{id}", name="edit")
     */

    public function edit(Event $event, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();

            // Génération d'un flash message à la modification d'un concert
            $this->addFlash(
                'success',
                'Le concert a bien été modifié !'
            );

            return $this->redirectToRoute('concert_admin_show');
        }

        // L'ajout et la modification sont gérées dans le même template
        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
            'tab_type' => 'Modifier',
        ]);
    }

    /**
     * Méthode gérant la suppression d'un concert existant
     * @Route("/delete/{id}", name="delete")
     */

    public function delete(Event $event, EntityManagerInterface $em)
    {
        if (!$event) {
            throw $this->createNotFoundException(
                'Le concert n\'existe pas ou a déjà été supprimé'
            );
        }

        $em->remove($event);
        $em->flush();

        // Génération d'un flash message à la suppression d'un concert
        $this->addFlash(
            'success',
            'Le concert a bien été supprimé'
        );

        // Pas de template associé à la suppression
        return $this->redirectToRoute('concert_admin_show');
    }
}
