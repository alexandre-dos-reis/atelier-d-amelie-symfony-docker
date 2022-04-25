<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ShopSubCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ShopSubCategoryRepository::class)
 */
class ShopSubCategory
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
     * @ORM\ManyToOne(targetEntity=ShopCategory::class, inversedBy="shopSubCategories")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("cartService")
     */
    private $shopCategory;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="shopSubCategory", orphanRemoval=true)
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getShopCategory(): ?ShopCategory
    {
        return $this->shopCategory;
    }

    public function setShopCategory(?ShopCategory $shopCategory): self
    {
        $this->shopCategory = $shopCategory;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setShopSubCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getShopSubCategory() === $this) {
                $product->setShopSubCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
