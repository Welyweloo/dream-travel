<?php

namespace App\Repository;

use App\Entity\CityLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CityLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityLike[]    findAll()
 * @method CityLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CityLike::class);
    }

    // /**
    //  * @return CityLike[] Returns an array of CityLike objects
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
    public function findOneBySomeField($value): ?CityLike
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
