<?php

namespace App\Repository;

use App\Entity\Identity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Identity>
 *
 * @method Identity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Identity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Identity[]    findAll()
 * @method Identity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Identity::class);
    }

    public function save(Identity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Identity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Identity[] Returns an array of Identity objects
    */
   public function findBySector($value): array
   {
       return $this->createQueryBuilder('i')
           ->andWhere('i.sector = :val')
           ->setParameter('val', $value)
           ->orderBy('i.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return Identity[] Returns an array of Identity objects
    */
   public function findAllValid(): array
   {
       return $this->createQueryBuilder('i')
           ->andWhere('i.fileName IS NOT NULL')
           ->orderBy('i.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   /**
    * @return Identity[] Returns an array of Identity objects
    */
   public function getIdentityByIa(string $tools): array
   {
       return $this->createQueryBuilder('i')
           ->innerJoin('i.aicores', 'a')
           ->where('a.slug = :val')
           ->andWhere('i.fileName IS NOT NULL')
           ->setParameter('val', $tools)
           ->orderBy('i.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Identity
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
