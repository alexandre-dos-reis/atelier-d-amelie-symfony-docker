<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cartService")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cartService")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("cartService")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Artwork::class, mappedBy="category")
     */
    private $artworks;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showInGallery;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disposition;

    public function __construct()
    {
        $this->artworks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Artwork[]
     */
    public function getArtworks(): Collection
    {
        return $this->artworks;
    }

    public function addArtwork(Artwork $artwork): self
    {
        if (!$this->artworks->contains($artwork)) {
            $this->artworks[] = $artwork;
            $artwork->addCategory($this);
        }

        return $this;
    }

    public function removeArtwork(Artwork $artwork): self
    {
        if ($this->artworks->removeElement($artwork)) {
            $artwork->removeCategory($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getShowInGallery(): ?bool
    {
        return $this->showInGallery;
    }

    public function setShowInGallery(bool $showInGallery): self
    {
        $this->showInGallery = $showInGallery;

        return $this;
    }

    public function getDisposition(): ?int
    {
        return $this->disposition;
    }

    public function setDisposition(?int $disposition): self
    {
        $this->disposition = $disposition;

        return $this;
    }
}
