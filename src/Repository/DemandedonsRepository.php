<?php

namespace App\Repository;

use App\Entity\Demandedons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demandedons>
 *
 * @method Demandedons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demandedons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demandedons[]    findAll()
 * @method Demandedons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandedonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demandedons::class);
    }

//    /**
//     * @return Demandedons[] Returns an array of Demandedons objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Demandedons
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
