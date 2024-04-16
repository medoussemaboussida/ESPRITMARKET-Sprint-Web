<?php

namespace App\Repository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Codepromo;

class CodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Codepromo::class);
    }

    // Méthode pour rechercher des codes en fonction de critères
    public function findByCriteria($code, $reductionassocie)
    {
        $qb = $this->createQueryBuilder('o');

        // Ajoutez des conditions pour filtrer les codes en fonction des critères fournis
        if ($code) {
            $qb->andWhere('o.code LIKE :code')
               ->setParameter('code', '%'.$code.'%');
        }

        if ($reductionassocie) {
            $qb->andWhere('o.reductionassocie = :reductionassocie')
               ->setParameter('reductionassocie', $reductionassocie);
        }

        return $qb->getQuery()->getResult();
    }
}
