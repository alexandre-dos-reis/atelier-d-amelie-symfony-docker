<?php

namespace App\Entity;

use App\Repository\ShippingCostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShippingCostRepository::class)
 */
class ShippingCost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $max;

    /**
     * @ORM\Column(type="integer")
     */
    private $insurance;

    /**
     * @ORM\Column(type="integer")
     */
    private $weightCost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getInsurance(): ?int
    {
        return $this->insurance;
    }

    public function setInsurance(int $insurance): self
    {
        $this->insurance = $insurance;

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
}
