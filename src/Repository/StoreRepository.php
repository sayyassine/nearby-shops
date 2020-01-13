<?php

namespace App\Repository;

use App\Entity\Store;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Store|null find($id, $lockMode = null, $lockVersion = null)
 * @method Store|null findOneBy(array $criteria, array $orderBy = null)
 * @method Store[]    findAll()
 * @method Store[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Store::class);
    }

    // /**
    //  * @return Store[] Returns an array of Store objects
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
    public function findOneBySomeField($value): ?Store
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findNearest($longitude , $latitude , $start = 0 , $limit = 20 , $type = null , $radius = null ){

        $builder = $this->createQueryBuilder('s')
            ->select("s as store")
            ->addSelect(
                '( 3959 * acos(cos(radians(' . $latitude . '))' .
                '* cos( radians( s.latitude ) )' .
                '* cos( radians( s.longitude )' .
                '- radians(' . $longitude . ') )' .
                '+ sin( radians(' . $latitude . ') )' .
                '* sin( radians( s.latitude ) ) ) ) as distance'
            )
            ->orderBy('distance', 'ASC')
            ->setFirstResult($start)
            ->setMaxResults($limit)
        ;


        if( !empty($type) ){
            $builder->andWhere('s.type = :type')
                ->setParameter('type' , $type);
        }

        if( !empty($radius) ){

            $builder->having('distance <= :distance')
                ->setParameter('distance' , $radius );

        }

        return $builder->getQuery()->getResult(Query::HYDRATE_OBJECT);

    }
    public function findNearestCount($longitude , $latitude , $type = null , $radius = null ){

        $builder = $this->createQueryBuilder('s')
            ->addSelect(
                '( 3959 * acos(cos(radians(' . $latitude . '))' .
                '* cos( radians( s.latitude ) )' .
                '* cos( radians( s.longitude )' .
                '- radians(' . $longitude . ') )' .
                '+ sin( radians(' . $latitude . ') )' .
                '* sin( radians( s.latitude ) ) ) ) as distance'
            )
            ->orderBy('distance', 'ASC')

        ;


        if( !empty($type) ){

            $builder->andWhere('s.type = :type')
                ->setParameter('type' , $type);

        }

        if( !empty($radius) ){

            $builder->having('distance <= :distance')
                ->setParameter('distance' , $radius );

        }

        $count = count($builder->getQuery()->getResult(Query::HYDRATE_OBJECT));

        return $count ;

    }


}
