<?php

namespace App\Interfaces;

use App\Entity\Card;

interface CardServiceInterface
{

    public function importOrUpdateCardsFromWebserviceToLocalSystem(string $webserviceUrl): void;

    public function getCards(array $criteria = [], string $orderBy = 'annualFee', string $direction = 'ASC'): array;

    public function updateCardDetails(int $cardId, array $data): Card;

    public function findCardById(int $cardId) : Card;

}