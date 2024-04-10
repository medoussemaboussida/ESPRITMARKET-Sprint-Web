<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * Récupérer les événements de type "dons"
     */
    public function findByTypeEv(string $typeEv): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.typeEv = :typeEv')
            ->setParameter('typeEv', $typeEv)
            ->getQuery()
            ->getResult();
    }
}
