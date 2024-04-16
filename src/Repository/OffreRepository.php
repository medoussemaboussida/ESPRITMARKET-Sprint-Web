<?php

namespace App\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Offre;

class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    // Méthode pour rechercher des offres en fonction de critères
    public function findByCriteria($nomOffre, $reduction)
    {
        $qb = $this->createQueryBuilder('o');

        // Ajoutez des conditions pour filtrer les offres en fonction des critères fournis
        if ($nomOffre) {
            $qb->andWhere('o.nomoffre LIKE :nomOffre')
               ->setParameter('nomOffre', '%'.$nomOffre.'%');
        }

        if ($reduction) {
            $qb->andWhere('o.reduction = :reduction')
               ->setParameter('reduction', $reduction);
        }

        return $qb->getQuery()->getResult();
    }
}
