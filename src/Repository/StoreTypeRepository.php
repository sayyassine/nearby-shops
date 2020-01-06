<?php

namespace App\Repository;

use App\Entity\StoreType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StoreType|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreType|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreType[]    findAll()
 * @method StoreType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreType::class);
    }

    // /**
    //  * @return StoreType[] Returns an array of StoreType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StoreType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
