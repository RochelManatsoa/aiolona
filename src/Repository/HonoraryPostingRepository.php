<?php

namespace App\Repository;

use App\Entity\HonoraryPosting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HonoraryPosting>
 *
 * @method HonoraryPosting|null find($id, $lockMode = null, $lockVersion = null)
 * @method HonoraryPosting|null findOneBy(array $criteria, array $orderBy = null)
 * @method HonoraryPosting[]    findAll()
 * @method HonoraryPosting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HonoraryPostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HonoraryPosting::class);
    }

    public function save(HonoraryPosting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HonoraryPosting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HonoraryPosting[] Returns an array of HonoraryPosting objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HonoraryPosting
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
