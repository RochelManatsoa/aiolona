<?php

namespace App\Repository;

use App\Data\SearchPostData;
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
    public function findValid(int $max = 10, int $offset = null): array
    {
        return $this->createQueryBuilder('p')
            ->select('p, COUNT(v.id) as HIDDEN num_views')
            ->leftJoin('p.views', 'v')
            ->andWhere('p.valid = 1')
            ->groupBy('p.id')
            ->orderBy('num_views', 'DESC')
            ->setMaxResults($max)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findBySkills($value): array
    {
        // Convertir la collection en tableau si nécessaire
        $skillIds = $value instanceof \Doctrine\Common\Collections\Collection ? $value->toArray() : $value;

        return $this->createQueryBuilder('p')
            ->select('s', 'p')
            ->join('p.skills', 's')
            ->andWhere('s.id IN (:skills)')
            ->setParameter('skills', $skillIds)
            ->getQuery()
            ->getResult();
    }

    public function findSearch(SearchPostData $seachData): array
    {

        $query = $this->createQueryBuilder('p')
            ->select('s, p, COUNT(v.id) as HIDDEN num_views')
            ->join('p.skills', 's')
        ;
        if(!empty($seachData->q)){
            $query = $query
                ->andWhere('p.title LIKE :q')
                ->setParameter('q', "%{$seachData->q}%");
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @return Postings[] Returns an array of Postings objects
     */
    public function findBySector($id,int $max = 10, int $offset = null): array
    {
        return $this->createQueryBuilder('p')
            ->select('s', 'p')
            ->join('p.sector', 's')
            ->andWhere('s.id IN (:sector)')
            ->setParameter('sector', $id)
            ->setMaxResults($max)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
        ;
    }
}
