<?php


namespace App\Repository;

use App\Entity\Demandedons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DemandedonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demandedons::class);
    }

    public function getDemandesAvecUtilisateurs(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT d, u.nomUser, u.prenomUser, d.nbpoints' .
            'FROM App\Entity\Demandedons d ' .
            'JOIN d.idUtilisateur u'
        );
    

        return $query->getResult();
    }
    public function countByEtat($etat)
{
    return $this->createQueryBuilder('d')
        ->select('COUNT(d)')
        ->andWhere('d.etatstatutdons = :etat')
        ->setParameter('etat', $etat)
        ->getQuery()
        ->getSingleScalarResult();
}

}
