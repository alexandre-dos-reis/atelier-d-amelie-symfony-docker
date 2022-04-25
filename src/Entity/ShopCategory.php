<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ShopCategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ShopCategoryRepository::class)
 */
class ShopCategory
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
     * @ORM\Column(type="string", length=255)
     * @Groups("cartService")
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disposition;

    /**
     * @ORM\OneToMany(targetEntity=ShopSubCategory::class, mappedBy="ShopCategory", orphanRemoval=false)
     */
    private $shopSubCategories;

    public function __construct()
    {
        $this->shopSubCategories = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * @return Collection|ShopSubCategory[]
     */
    public function getShopSubCategories(): Collection
    {
        return $this->shopSubCategories;
    }

    public function addShopSubCategory(ShopSubCategory $shopSubCategory): self
    {
        if (!$this->shopSubCategories->contains($shopSubCategory)) {
            $this->shopSubCategories[] = $shopSubCategory;
            $shopSubCategory->setShopCategory($this);
        }

        return $this;
    }

    public function removeShopSubCategory(ShopSubCategory $shopSubCategory): self
    {
        if ($this->shopSubCategories->removeElement($shopSubCategory)) {
            // set the owning side to null (unless already changed)
            if ($shopSubCategory->getShopCategory() === $this) {
                $shopSubCategory->setShopCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
