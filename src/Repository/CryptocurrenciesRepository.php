<?php

namespace App\Repository;

use App\Entity\Cryptocurrencies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cryptocurrencies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cryptocurrencies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cryptocurrencies[]    findAll()
 * @method Cryptocurrencies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptocurrenciesRepository extends ServiceEntityRepository
{
    private $query;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cryptocurrencies::class);
    }

    // /**
    //  * @return Cryptocurrencies[] Returns an array of Cryptocurrencies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cryptocurrencies
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByNameOrFullname(String $value)
    {   
        if (strlen($value) <= 3) {
            $qb = $this->createQueryBuilder('c')
                ->where('c.name = :name')
                ->setParameter('name', $value)
            ;

            $query = $qb->getQuery();

            $this->query = $query->getOneOrNullResult();

        } elseif (strlen($value) > 3) {
            $qb = $this->createQueryBuilder('c')
                ->where('c.fullname = :fullname')
                ->setParameter('fullname', $value)
            ;

            $query = $qb->getQuery();

            $this->query = $query->getOneOrNullResult();

        }

        if (isset($this->query)) {
            return $this->query;
        } elseif (!isset($this->query)) {

            return false; 
        }

    }
}
