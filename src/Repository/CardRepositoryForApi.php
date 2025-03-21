<?php

namespace App\Repository;

use App\Entity\Bank;
use App\Entity\Card;
use App\Enums\CardType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CardRepositoryForApi
{
    public function __construct(private BankRepository $bankRepository, private CardRepository $cardRepository){}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function import(string $source): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $source);
        $xml = simplexml_load_string($response->getContent());
        foreach ($xml->product as $product) {
            $this->update((array)$product);
        }
    }

    public function update(array $data): void
    {
        $bank = $this->bankRepository->findOneBy(['bankId' => $data['bankid']]) ?? new Bank();
        $bank->setName($data['bank']);
        $bank->setBankId($data['bankid']);
        $this->bankRepository->save($bank);
        $card = $this->cardRepository->findOneBy(['productId' => $data['productid']]) ?? new Card();
        $card->setProductId($data['productid'])
            ->setName($data['produkt'])
            ->setLink($data['link'])
            ->setLogo($data['logo'])
            ->setBank($bank)
            ->setAnnualFee((float) $data['gebuehren'])
            ->setCosts((float) $data['kosten'])
            ->setRemarks(htmlspecialchars_decode($data['anmerkungen']) ?? null)
            ->setRating(isset($data['bewertung']) ? (string) $data['bewertung'] : null)
            ->setTae(isset($data['incentive']) ? (float) $data['incentive'] : null)
            ->setInsurances(isset($data['insurances']) ? (bool) $data['insurances'] : null)
            ->setBenefits(isset($data['benefits']) ? (bool) $data['benefits'] : null)
            ->setServices(isset($data['services']) ? (bool) $data['services'] : null)
            ->setSpecialFeature(htmlspecialchars_decode($data['besonderheiten']) ?? null)
            ->setCardType(isset($data['cardtype']) ? CardType::from((int) $data['cardtype']) : null)
            ->setFirstYearFee(isset($data['gebuehrenjahr1']) ? (float) $data['gebuehrenjahr1'] : null)
            ->setFromSecondYearFee(isset($data['dauerhaft']) ? (float) $data['dauerhaft'] : null);
        $this->cardRepository->save($card);
    }
}