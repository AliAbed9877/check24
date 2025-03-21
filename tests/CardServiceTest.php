<?php

namespace App\Tests;

use App\Entity\Card;
use App\Entity\CardChange;
use App\Repository\CardChangeRepository;
use App\Repository\CardRepository;
use App\Repository\CardRepositoryForApi;
use App\Service\CardService;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CardServiceTest extends TestCase
{
    private MockObject $cardRepository;
    private MockObject $cardRepositoryForApi;
    private MockObject $cardChangeRepository;
    private CardService $cardService;

    protected function setUp(): void
    {
        $this->cardRepository = $this->createMock(CardRepository::class);
        $this->cardRepositoryForApi = $this->createMock(CardRepositoryForApi::class);
        $this->cardChangeRepository = $this->createMock(CardChangeRepository::class);

        $this->cardService = new CardService(
            $this->cardRepository,
            $this->cardRepositoryForApi,
            $this->cardChangeRepository
        );
    }

    public function testImportOrUpdateCardsSuccess(): void
    {
        $url = 'https://tools.financeads.net/webservice.php?wf=1&format=xml&calc=kreditkarterechner&country=ES';

        $this->cardRepositoryForApi
            ->expects($this->once())
            ->method('import')
            ->with($url);

        $this->cardService->importOrUpdateCardsFromWebserviceToLocalSystem($url);
        $this->addToAssertionCount(1);
    }

    public function testImportOrUpdateCardsFailure(): void
    {
        $url = 'https://example.com/webservice';

        $this->cardRepositoryForApi
            ->method('import')
            ->willThrowException(new \Exception('API error'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to import cards: API error');

        $this->cardService->importOrUpdateCardsFromWebserviceToLocalSystem($url);
    }

    public function testGetCardsWithValidParameters(): void
    {
        $criteria = ['cardType' => 'debit'];
        $cards = [new Card(), new Card()];

        $this->cardRepository
            ->expects($this->once())
            ->method('findByCriteria')
            ->with($criteria, ['annualFee' => 'ASC'])
            ->willReturn($cards);

        $result = $this->cardService->getCards($criteria);
        $this->assertSame($cards, $result);
    }

    public function testUpdateCardDetailsSuccess(): void
    {
        $cardId = 1;
        $data = ['columnName' => 'name', 'value' => 'New Card Name'];
        $card = new Card();

        $this->cardRepository
            ->expects($this->once())
            ->method('find')
            ->with($cardId)
            ->willReturn($card);

        $this->cardChangeRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($cardChange) use ($card) {
                return $cardChange instanceof CardChange &&
                    $cardChange->getCard() === $card &&
                    $cardChange->getColumnName() === 'name' &&
                    $cardChange->getValue() === 'New Card Name';
            }));

        $result = $this->cardService->updateCardDetails($cardId, $data);
        $this->assertSame($card, $result);
    }
    public function testUpdateCardDetailsInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields: columnName and value');

        $this->cardService->updateCardDetails(1, []);
    }

    public function testFindCardByIdNotFound(): void
    {
        $cardId = 999;

        $this->cardRepository
            ->expects($this->once())
            ->method('find')
            ->with($cardId)
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Card with ID {$cardId} not found");

        $this->cardService->findCardById($cardId);
    }
}