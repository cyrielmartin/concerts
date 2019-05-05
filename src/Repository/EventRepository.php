<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // Méthode gérant l'affichage par défaut des concerts dans la partie Admin : ordre chronologique
    public function showEventOrderDateAsc()
    {
        return $this
            ->createQueryBuilder('events')
            ->orderBy('events.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Méthode gérant l'affichage par défaut des concerts sur la page d'accueil : trois prochains mois uniquement, ordre chronologique
    public function showEventsLastThreeMonths()
    {
        return $this
            ->createQueryBuilder('events')
            ->andWhere('events.date > :olderThan')
            ->setParameter('olderThan', new \DateTime)
            ->andWhere('events.date < :youngerThan')
            ->setParameter('youngerThan', new \DateTime('3 months'))
            ->orderBy('events.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
