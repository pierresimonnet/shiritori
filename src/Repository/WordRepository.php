<?php

namespace App\Repository;

use App\Entity\Shiritori;
use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

    /**
     * @param Shiritori $shiritori
     * @return Word|null
     * @throws NonUniqueResultException
     */
    public function findLastWord(Shiritori $shiritori)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.shiritori = :shiritori')
            ->setParameter('shiritori', $shiritori)
            ->orderBy('w.id', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param string $value
     * @param Shiritori $shiritori
     * @return Word|null
     * @throws NonUniqueResultException
     */
    public function findOneByWordAndShiritori($value, $shiritori): ?Word
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.word = :val')
            ->andWhere('w.shiritori = :shiritori')
            ->setParameter('val', $value)
            ->setParameter('shiritori', $shiritori)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Word[] Returns an array of Word objects
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
}
