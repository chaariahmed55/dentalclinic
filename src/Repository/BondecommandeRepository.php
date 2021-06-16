<?php

namespace App\Repository;

use App\Entity\Bondecommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bondecommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bondecommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bondecommande[]    findAll()
 * @method Bondecommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BondecommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bondecommande::class);
    }

    // /**
    //  * @return Bondecommande[] Returns an array of Bondecommande objects
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
    public function findOneBySomeField($value): ?Bondecommande
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
