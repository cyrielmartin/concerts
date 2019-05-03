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

    // Méthode permettant l'affichage des concerts par ordre antéchronologique
    public function showEventOrderDateDesc()
    {
        return $this
            ->createQueryBuilder('events')
            ->orderBy('events.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Méthode permettant l'affichage par défaut des concerts : trois prochains mois uniquement, ordre chronologique
    public function showEventsLastThreeMonths()
    {
        return $this
            ->createQueryBuilder('events')
            ->andWhere('events.date > :olderThan')
            ->setParameter('olderThan', new \DateTime)
            ->andWhere('events.date < :youngerThan')
            ->setParameter('youngerThan', new \DateTime('3 months'))
            ->getQuery()
            ->getResult();
    }
}
