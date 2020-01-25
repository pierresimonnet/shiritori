<?php

namespace App\Repository;

use App\Entity\Shiritori;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Shiritori|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shiritori|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shiritori[]    findAll()
 * @method Shiritori[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiritoriRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shiritori::class);
    }

    // /**
    //  * @return Shiritori[] Returns an array of Shiritori objects
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
    public function findOneBySomeField($value): ?Shiritori
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
