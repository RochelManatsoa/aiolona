<?php

namespace App\Repository;

use App\Entity\AIcores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AIcores>
 *
 * @method AIcores|null find($id, $lockMode = null, $lockVersion = null)
 * @method AIcores|null findOneBy(array $criteria, array $orderBy = null)
 * @method AIcores[]    findAll()
 * @method AIcores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AIcoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AIcores::class);
    }

    public function save(AIcores $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AIcores $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return AIcores[] Returns an array of AIcores objects
    */
   public function findSearch($value, int $max = 13, int $offset = null): array
   {
       return $this->createQueryBuilder('a')
           ->andWhere('a.type = :val')
           ->andWhere('a.slogan IS NOT NULL')
           ->andWhere('a.image IS NOT NULL')
           ->setParameter('val', $value)
           ->orderBy('a.id', 'ASC')
           ->setMaxResults($max)
           ->setFirstResult($offset)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?AIcores
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
