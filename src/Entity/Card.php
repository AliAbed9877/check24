<?php

namespace App\Entity;

use App\Enums\CardType;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $productId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'Cards')]
    private ?Bank $bank = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $annualFee = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $costs = null;

    #[ORM\Column(length: 1000)]
    private ?string $remarks = null;

    #[ORM\Column(length: 255)]
    private ?string $rating = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?float $tae = null;

    #[ORM\Column(nullable: true)]
    private ?bool $insurances = null;

    #[ORM\Column(nullable: true)]
    private ?bool $benefits = null;

    #[ORM\Column(nullable: true)]
    private ?bool $services = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $specialFeature = null;

    #[ORM\Column(type: 'integer', enumType: CardType::class)]
    private $cardType;


    #[ORM\Column(nullable: true)]
    private ?float $firstYearFee = null;

    #[ORM\Column(nullable: true)]
    private ?float $fromSecondYearFee = null;

    /**
     * @var Collection<int, CardChange>
     */
    #[ORM\OneToMany(targetEntity: CardChange::class, mappedBy: 'card')]
    private Collection $cardChanges;

    public function __construct()
    {
        $this->cardChanges = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): static
    {
        $this->bank = $bank;

        return $this;
    }

    public function getAnnualFee(): ?float
    {
        return $this->annualFee;
    }

    public function setAnnualFee(float $annualFee): static
    {
        $this->annualFee = $annualFee;

        return $this;
    }

    public function getCosts(): ?float
    {
        return $this->costs;
    }

    public function setCosts(float $costs): static
    {
        $this->costs = $costs;

        return $this;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function setRemarks(string $remarks): static
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getTae(): ?float
    {
        return $this->tae;
    }

    public function setTae(?float $tae): static
    {
        $this->tae = $tae;

        return $this;
    }

    public function isInsurances(): ?bool
    {
        return $this->insurances;
    }

    public function setInsurances(?bool $insurances): static
    {
        $this->insurances = $insurances;

        return $this;
    }

    public function isBenefits(): ?bool
    {
        return $this->benefits;
    }

    public function setBenefits(?bool $benefits): static
    {
        $this->benefits = $benefits;

        return $this;
    }

    public function isServices(): ?bool
    {
        return $this->services;
    }

    public function setServices(?bool $services): static
    {
        $this->services = $services;

        return $this;
    }

    public function getSpecialFeature(): ?string
    {
        return $this->specialFeature;
    }

    public function setSpecialFeature(?string $specialFeature): static
    {
        $this->specialFeature = $specialFeature;

        return $this;
    }

    public function getCardType(): ?CardType
    {
        return $this->cardType;
    }

    public function setCardType(CardType|int $cardType): self
    {
        if (is_int($cardType)) {
            $this->cardType = CardType::from($cardType);
        } else {
            $this->cardType = $cardType;
        }
        return $this;
    }

    public function getCardTypeLabel(): ?string
    {
        return $this->cardType?->getLabel();
    }
    public function getFirstYearFee(): ?float
    {
        return $this->firstYearFee;
    }

    public function setFirstYearFee(?float $firstYearFee): static
    {
        $this->firstYearFee = $firstYearFee;

        return $this;
    }

    public function getFromSecondYearFee(): ?float
    {
        return $this->fromSecondYearFee;
    }

    public function setFromSecondYearFee(?float $fromSecondYearFee): static
    {
        $this->fromSecondYearFee = $fromSecondYearFee;

        return $this;
    }

    /**
     * @return Collection<int, CardChange>
     */
    public function getCardChanges(): Collection
    {
        return $this->cardChanges;
    }

    public function addCardChange(CardChange $cardChange): static
    {
        if (!$this->cardChanges->contains($cardChange)) {
            $this->cardChanges->add($cardChange);
            $cardChange->setCard($this);
        }

        return $this;
    }

    public function removeCardChange(CardChange $cardChange): static
    {
        if ($this->cardChanges->removeElement($cardChange)) {
            // set the owning side to null (unless already changed)
            if ($cardChange->getCard() === $this) {
                $cardChange->setCard(null);
            }
        }

        return $this;
    }


}
