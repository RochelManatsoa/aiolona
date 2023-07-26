<?php

namespace App\Repository;

use App\Entity\Posting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posting>
 *
 * @method Posting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posting[]    findAll()
 * @method Posting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posting::class);
    }

    public function save(Posting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Posting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Posting[] Returns an array of Posting objects
    */
   public function findValid(): array
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.valid = 1')
           ->orderBy('p.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Posting
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
