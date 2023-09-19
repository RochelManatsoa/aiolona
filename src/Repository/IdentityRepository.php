<?php

namespace App\Repository;

use App\Data\SeachData;
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
   public function findSearch(SeachData $seachData, int $max = 13, int $offset = null): array
   {
        $query = $this->createQueryBuilder('i')
            ->select('i, s, COUNT(v.id) as HIDDEN num_views')
            ->join('i.sectors', 's')
            ->join('i.aicores', 'a')
            ->join('i.languages', 'la')
            ->join('la.lang', 'l')
            ->leftJoin('i.identityViews', 'v')
            ->andWhere('i.fileName IS NOT NULL')
            ->andWhere('i.username IS NOT NULL')
            ->groupBy('i.id, s.id')
            ->orderBy('num_views', 'DESC')
            ->setMaxResults($max)
            ->setFirstResult($offset)
        ;

        if(!empty($seachData->q)){
            $query = $query
                ->andWhere('i.firstName LIKE :q')
                ->setParameter('q', "%{$seachData->q}%");
        }

        if(!empty($seachData->min)){
            $query = $query
                ->andWhere('i.tarif >= :min')
                ->setParameter('min', $seachData->min);
        }

        if(!empty($seachData->max)){
            $query = $query
                ->andWhere('i.tarif < :max')
                ->setParameter('max', $seachData->max);
        }

        if(!empty($seachData->sectors)){
            $query = $query
                ->andWhere('s.id IN (:sectors)')
                ->setParameter('sectors', $seachData->sectors);
        }

        if(!empty($seachData->aicores)){
            $query = $query
                ->andWhere('a.id IN (:aicores)')
                ->setParameter('aicores', $seachData->aicores);
        }

        if(!empty($seachData->langues)){
            $query = $query
                ->andWhere('l.id IN (:langues)')
                ->setParameter('langues', $seachData->langues);
        }

       return $query->getQuery()->getResult();
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

   public function findTopRanked() : array
   {
        return $this->createQueryBuilder('i')
            ->select('i, COUNT(l.id) as HIDDEN num_likes, COUNT(v.id) as HIDDEN num_views')
            ->leftJoin('i.likes', 'l')  
            ->leftJoin('i.identityViews', 'v')  
            ->groupBy('i')
            ->orderBy('num_views', 'DESC') 
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
