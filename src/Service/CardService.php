<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\CardChange;
use App\Interfaces\CardServiceInterface;
use App\Repository\CardChangeRepository;
use App\Repository\CardRepository;
use App\Repository\CardRepositoryForApi;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 * Service class for managing credit card operations.
 *
 * @readonly
 */
class CardService implements CardServiceInterface
{
    /**
     * @var array<string> Allowed fields for ordering cards
     */
    private const ALLOWED_ORDER_FIELDS = ['annualFee', 'name'];

    /**
     * @param CardRepository $cardRepository Repository for card operations
     * @param CardRepositoryForApi $cardRepositoryForApi Repository for API interactions
     * @param CardChangeRepository $cardChangeRepository Repository for tracking card changes
     */
    public function __construct(
        private readonly CardRepository       $cardRepository,
        private readonly CardRepositoryForApi $cardRepositoryForApi,
        private readonly CardChangeRepository $cardChangeRepository
    ) {
    }

    /**
     * Imports or updates credit cards from a webservice URL.
     *
     * @param string $webserviceUrl The URL of the webservice providing card data
     * @throws RuntimeException If the import process fails
     */
    public function importOrUpdateCardsFromWebserviceToLocalSystem(string $webserviceUrl): void
    {
        try {
            $this->cardRepositoryForApi->import($webserviceUrl);
        } catch (Exception $e) {
            throw new RuntimeException('Failed to import cards: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Retrieves cards based on criteria with sorting options.
     *
     * @param array<string, mixed> $criteria Filter criteria for card selection
     * @param string $orderBy Field to sort by
     * @param string $direction Sort direction (ASC or DESC)
     * @return Card[] Array of matching cards
     */
    public function getCards(array $criteria = [], string $orderBy = 'annualFee', string $direction = 'ASC'): array
    {
        $orderBy = in_array($orderBy, self::ALLOWED_ORDER_FIELDS) ? $orderBy : 'annualFee';
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        return $this->cardRepository->findByCriteria($criteria, [$orderBy => $direction]);
    }

    /**
     * Updates card details and logs the change.
     *
     * @param int $cardId ID of the card to update
     * @param array<string, mixed> $data Update data containing columnName and value
     * @return Card Updated card entity
     * @throws InvalidArgumentException If data is invalid or card not found
     */
    public function updateCardDetails(int $cardId, array $data): Card
    {
        if (empty($data['columnName']) || !array_key_exists('value', $data)) {
            throw new InvalidArgumentException('Missing required fields: columnName and value');
        }

        $card = $this->findCardById($cardId);

        $cardChange = new CardChange();
        $cardChange->setCard($card)
            ->setColumnName($data['columnName'])
            ->setValue($data['value'])
            ->setCreatedAt(new DateTimeImmutable());

        $this->cardChangeRepository->save($cardChange);

        return $cardChange->getCard();
    }

    /**
     * Finds a card by its ID.
     *
     * @param int $cardId The ID of the card to find
     * @return Card The found card entity
     * @throws InvalidArgumentException If card is not found
     */
    public function findCardById(int $cardId): Card
    {
        $card = $this->cardRepository->find($cardId);
        if (!$card) {
            throw new InvalidArgumentException("Card with ID {$cardId} not found");
        }
        return $card;
    }

    public function getChangesForCard(int $cardId): array
    {
        $changedCard = $this->cardChangeRepository->findChangesByCardId($cardId);
        if (empty($changedCard)) {
            throw new InvalidArgumentException("No changes found for {$cardId}");
        }
        return $changedCard;
    }
}