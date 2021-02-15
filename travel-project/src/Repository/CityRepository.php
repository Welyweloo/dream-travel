<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findByGeonameId($geonameId){
        $builder = $this->createQueryBuilder('city');
        $builder->where('city.geonameId = :geonameId');
        $builder->setParameter('geonameId', $geonameId);
        $query = $builder->getQuery();
       
        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function findByPartialName($partialName){
        $builder = $this->createQueryBuilder('city');
        $builder->where('city.cityName LIKE :partialName');
        $builder->setParameter('partialName',$partialName.'%');
        $builder->orderBy('city.cityName', 'ASC');
        $query = $builder->getQuery();
       
        return $query->execute();
    }

    public function findByPartialCountryName($partialName){
        $builder = $this->createQueryBuilder('city');
        $builder->distinct(true);
        $builder->where('city.countryName LIKE :partialName');
        $builder->setParameter('partialName',$partialName.'%');
        $builder->orderBy('city.countryName', 'ASC');
        $builder->groupBy('city.countryName');
        $query = $builder->getQuery();
       
        return $query->execute();
    }

    public function findByCountryName($countryName){
        $builder = $this->createQueryBuilder('city');
        $builder->where('city.countryName LIKE :countryName');
        $builder->setParameter('countryName',$countryName);
        $builder->orderBy('city.cityName', 'ASC');
        $query = $builder->getQuery();
       
        return $query->execute();
    }

    // /**
    //  * @return City[] Returns an array of City objects
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
    public function findOneBySomeField($value): ?City
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
