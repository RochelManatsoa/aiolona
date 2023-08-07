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
    public function findValid(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.valid = 1')
            ->orderBy('p.id', 'ASC')
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
            ->select('s', 'p')
            ->join('p.skills', 's')
        ;
        if(!empty($seachData->q)){
            $query = $query
                ->andWhere('p.title LIKE :q')
                ->setParameter('q', "%{$seachData->q}%");
        }

        return $query->getQuery()->getResult();
    }
}
