<?php

namespace App\Repository;

use App\Entity\BonCommandeDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BonCommandeDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method BonCommandeDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method BonCommandeDetail[]    findAll()
 * @method BonCommandeDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonCommandeDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BonCommandeDetail::class);
    }

    // /**
    //  * @return BonCommandeDetail[] Returns an array of BonCommandeDetail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BonCommandeDetail
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
