<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PurchaseRepository;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stripeId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $purchasedAt;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingNumber;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseItem::class, mappedBy="purchase", orphanRemoval=true, cascade={"persist"})
     */
    private $purchaseItems;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseAddress::class, mappedBy="purchase", orphanRemoval=true, cascade={"persist"})
     */
    private $purchaseAddresses;

    /**
     * @ORM\Column(type="integer")
     */
    private $insuranceCost;

    /**
     * @ORM\Column(type="integer")
     */
    private $weightCost;

    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
        $this->purchaseAddresses = new ArrayCollection();
    }

    public const STATUS_ARRAY = [
        0 => "En attente de paiement",
        1 => "En cours de préparation",
        2 => "En cours de livraison",
        3 => "Livré",
        4 => "Annulée",
        5 => "Remboursée"
    ];

    public const PAIEMENT_EN_ATTENTE = self::STATUS_ARRAY[0];
    public const PREPARATION_EN_COURS = self::STATUS_ARRAY[1];
    public const LIVRAISON_EN_COURS = self::STATUS_ARRAY[2];
    public const LIVRE = self::STATUS_ARRAY[3];
    public const ANNULE = self::STATUS_ARRAY[4];
    public const REMBOURSEE = self::STATUS_ARRAY[5];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if (!in_array($status, self::STATUS_ARRAY)) {
            throw new \InvalidArgumentException("Le statut est invalide.");
        }
        $this->status = $status;

        return $this;
    }

    public static function getEasyAdminStatus(): array
    {
        $array = [];
        foreach (self::STATUS_ARRAY as $value) {
            $array[$value] = $value;
        }
        return $array;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    // On sauvegarde dans la BDD un UUID en base58
    public function setUuid(): self
    {
        $this->uuid = Uuid::v4()->toBase58();

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getPurchasedAt(): ?\DateTimeImmutable
    {
        return $this->purchasedAt;
    }

    public function setPurchasedAt(): self
    {
        $this->purchasedAt = new DateTimeImmutable();

        return $this;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(?string $trackingNumber): self
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    /**
     * @return Collection|PurchaseItem[]
     */
    public function getPurchaseItems(): Collection
    {
        return $this->purchaseItems;
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self
    {
        if (!$this->purchaseItems->contains($purchaseItem)) {
            $this->purchaseItems[] = $purchaseItem;
            $purchaseItem->setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchaseItems->removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getPurchase() === $this) {
                $purchaseItem->setPurchase(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PurchaseAddress[]
     */
    public function getPurchaseAddresses(): Collection
    {
        return $this->purchaseAddresses;
    }

    public function addPurchaseAddress(PurchaseAddress $purchaseAddress): self
    {
        if (!$this->purchaseAddresses->contains($purchaseAddress)) {
            $this->purchaseAddresses[] = $purchaseAddress;
            $purchaseAddress->setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseAddress(PurchaseAddress $purchaseAddress): self
    {
        if ($this->purchaseAddresses->removeElement($purchaseAddress)) {
            // set the owning side to null (unless already changed)
            if ($purchaseAddress->getPurchase() === $this) {
                $purchaseAddress->setPurchase(null);
            }
        }

        return $this;
    }

    public function getInsuranceCost(): ?int
    {
        return $this->insuranceCost;
    }

    public function setInsuranceCost(int $insuranceCost): self
    {
        $this->insuranceCost = $insuranceCost;

        return $this;
    }

    public function getWeightCost(): ?int
    {
        return $this->weightCost;
    }

    public function setWeightCost(int $weightCost): self
    {
        $this->weightCost = $weightCost;

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(string $stripeId): self
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    public function getPdfFilename(): string
    {
        return 'facture-'
            . $this->getPurchasedAt()->format('Y-m-d')
            . '-'
            . $this->getUuid()
            . '-'
            . $this->getStripeId();
    }
}
