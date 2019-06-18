<?php

namespace App\Repository;

use App\Entity\Canopy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Canopy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Canopy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Canopy[]    findAll()
 * @method Canopy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CanopyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Canopy::class);
    }

    // /**
    //  * @return Canopy[] Returns an array of Canopy objects
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
    public function findOneBySomeField($value): ?Canopy
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findSumByMonth($year=null, $month=null)
    {
        $year = isset($year) ? $year : date('Y');
        $month = isset($month) ? $month : date('m');
        $start = new \DateTime();
        $start->setDate((int)$year,(int)$month,1);
        $interval = new \DateInterval('P1M');
        return $this->createQueryBuilder('c')
            ->select('c.number, SUM(r.count) as sum')
            ->innerJoin('c.rents', 'r')
            ->where('r.rentDate >= :start and r.rentDate <= :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $start->add($interval)->format('Y-m-d H:i:s'))
            ->groupBy('c.id')
            ->orderBy('c.number')
            ->getQuery()
            ->getArrayResult()
            ;
    }
}
