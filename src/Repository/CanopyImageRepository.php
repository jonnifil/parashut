<?php

namespace App\Repository;

use App\Entity\CanopyImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CanopyImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CanopyImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CanopyImage[]    findAll()
 * @method CanopyImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CanopyImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CanopyImage::class);
    }

    // /**
    //  * @return CanopyImage[] Returns an array of CanopyImage objects
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
    public function findOneBySomeField($value): ?CanopyImage
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
