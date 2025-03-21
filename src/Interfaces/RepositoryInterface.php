<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function findByCriteria(array $criteria, array $orderBy = null): array;
    public function save(object $entity): void;
}