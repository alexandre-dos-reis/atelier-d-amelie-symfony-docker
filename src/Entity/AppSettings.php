<?php

namespace App\Entity;

use App\Repository\AppSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppSettingsRepository::class)
 */
class AppSettings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $cgv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCgv(): ?string
    {
        return $this->cgv;
    }

    public function setCgv(string $cgv): self
    {
        $this->cgv = $cgv;

        return $this;
    }
}
