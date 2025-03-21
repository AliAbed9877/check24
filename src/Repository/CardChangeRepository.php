<?php

namespace App\Repository;

use App\Entity\CardChange;
use App\Interfaces\RepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CardChange>
 */
class CardChangeRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardChange::class);
    }

    public function findByCriteria(array $criteria, array $orderBy = null): array
    {
        $qb = $this->createQueryBuilder('cc')
            ->leftJoin('cc.card', 'c');

        if (!empty($criteria['cardId'])) {
            $qb->andWhere('c.id = :cardId')
                ->setParameter('cardId', $criteria['cardId']);
        }

        if (!empty($criteria['columnName'])) {
            $qb->andWhere('cc.columnName = :columnName')
                ->setParameter('columnName', $criteria['columnName']);
        }

        if ($orderBy) {
            foreach ($orderBy as $field => $direction) {
                $qb->orderBy("cc.$field", $direction);
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function save(object $entity): void
    {
        if (!$entity instanceof CardChange) {
            throw new \InvalidArgumentException('Entity must be an instance of CardChange');
        }
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
    /**
     * Fetch change history for a specific card, ordered by creation date descending.
     *
     * @param int $cardId
     * @return CardChange[]
     */
    public function findChangesByCardId(int $cardId): array
    {
        return $this->createQueryBuilder('cc')
            ->where('cc.card = :cardId')
            ->setParameter('cardId', $cardId)
            ->orderBy('cc.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
