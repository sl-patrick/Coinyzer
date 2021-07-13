<?php

namespace App\Repository;

use App\Entity\Watchlists;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Watchlists|null find($id, $lockMode = null, $lockVersion = null)
 * @method Watchlists|null findOneBy(array $criteria, array $orderBy = null)
 * @method Watchlists[]    findAll()
 * @method Watchlists[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WatchlistsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Watchlists::class);
    }

    // /**
    //  * @return Watchlists[] Returns an array of Watchlists objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Watchlists
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
