<?php
namespace App\Repository;

use App\Entity\Bank;
use App\Interfaces\RepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BankRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bank::class);
    }

    /**
     * Find banks by criteria with optional ordering
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @return Bank[]
     */
    public function findByCriteria(array $criteria, array $orderBy = null): array
    {
        return $this->findBy($criteria, $orderBy);
    }

    /**
     * Save a bank entity to the database
     *
     * @param object $entity
     */
    public function save(object $entity): void
    {
        if (!$entity instanceof Bank) {
            throw new \InvalidArgumentException('Entity must be an instance of Bank');
        }

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Find a bank by its external bank ID
     *
     * @param int $bankId
     * @return Bank|null
     */
    public function findOneByBankId(int $bankId): ?Bank
    {
        return $this->findOneBy(['bankId' => $bankId]);
    }
}