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




public function findByCriteriaTriReduction(array $criteria, $sortBy = 'reductionassocie', $sortOrder = 'asc')
{
    $qb = $this->createQueryBuilder('o');

    // Ajoutez des conditions pour filtrer les codes en fonction des critères fournis
    foreach ($criteria as $field => $value) {
        $qb->andWhere('o.'.$field.' = :'.$field)
           ->setParameter($field, $value);
    }

    // Ajoutez le tri
    $qb->orderBy('o.'.$sortBy, $sortOrder);

    return $qb->getQuery()->getResult();
}

}
