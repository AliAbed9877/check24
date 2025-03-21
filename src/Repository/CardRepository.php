<?php
namespace App\Repository;

use App\Entity\Card;
use App\Enums\CardType;
use App\Interfaces\RepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CardRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

 /*
  * for whole of the project i implement this function for filtering as well
  * but you didn't mention if you need filter or no
  * but i just implement that in the code
  */
    public function findByCriteria(array $criteria, array $orderBy = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.bank', 'b');

        if (!empty($criteria['cardType'])) {
            $qb->andWhere('c.cardType IN (:cardType)')
                ->setParameter('cardType', $criteria['cardType']);
        }

        if (!empty($criteria['tae'])) {
            $taeConditions = [];
            foreach ($criteria['tae'] as $range) {
                if ($range === 'less_15') {
                    $taeConditions[] = 'c.tae < 15';
                } elseif ($range === '15_30') {
                    $taeConditions[] = 'c.tae BETWEEN 15 AND 30';
                } elseif ($range === 'more_30') {
                    $taeConditions[] = 'c.tae > 30';
                }
            }
            if (!empty($taeConditions)) {
                $qb->andWhere('(' . implode(' OR ', $taeConditions) . ')');
            }
        }

        if (!empty($criteria['bank'])) {
            $qb->andWhere('b.id IN (:bankIds)')
                ->setParameter('bankIds', $criteria['bank']);
        }
        if ($orderBy) {
            foreach ($orderBy as $field => $direction) {
                $qb->orderBy("c.$field", $direction);
            }
        }
        return $qb->getQuery()->getResult();
    }

    public function save(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}