<?php

namespace App\Repository;

use App\Entity\CryptocurrencyData;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method CryptocurrencyData|null find($id, $lockMode = null, $lockVersion = null)
 * @method CryptocurrencyData|null findOneBy(array $criteria, array $orderBy = null)
 * @method CryptocurrencyData[]    findAll()
 * @method CryptocurrencyData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptocurrencyDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CryptocurrencyData::class);
    }

    // /**
    //  * @return CryptocurrencyData[] Returns an array of CryptocurrencyData objects
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
    public function findOneBySomeField($value): ?CryptocurrencyData
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function fetchData()
    {
        $qb = $this->createQueryBuilder('d')
            ->join('d.cryptocurrencies', 'c')
            ->where('d.cryptocurrencies = c.id')
            ->orderBy('d.market_cap', 'DESC');
        
        $query = $qb->getQuery();

        return $query;

    }

    public function fetchDataByIds(string $ids)
    {
        $qb = $this->createQueryBuilder('d')
            ->join('d.cryptocurrencies', 'c')
            ->add('where', "d.cryptocurrencies IN ($ids)")
            ->orderBy('d.market_cap', 'DESC');

        $query = $qb->getQuery();

        return $query;
    }
}
