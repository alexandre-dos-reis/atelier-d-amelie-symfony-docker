<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use App\Repository\ProductRepository;
use App\Service\ImageEditor\ImageEditorInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cartService")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups("cartService")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups("cartService")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $forSale;

    /**
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Artwork::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("cartService")
     */
    private $artwork;

    /**
     * @ORM\OneToMany(targetEntity=ImageProduct::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     * @OrderBy({"disposition" = "ASC"})
     * @Groups("cartService")
     */
    private $imageProducts;

    /**
     * @ORM\Column(type="integer")
     * @Groups("cartService")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=ShopSubCategory::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("cartService")
     */
    private $shopSubCategory;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseItem::class, mappedBy="product", cascade={"remove"})
     */
    private $purchaseItems;

    public function __construct()
    {
        $this->imageProducts = new ArrayCollection();
        $this->purchaseItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getForSale(): ?bool
    {
        return $this->forSale;
    }

    public function setForSale(bool $forSale): self
    {
        $this->forSale = $forSale;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getArtwork(): ?Artwork
    {
        return $this->artwork;
    }

    public function setArtwork(?Artwork $artwork): self
    {
        $this->artwork = $artwork;

        return $this;
    }

    /**
     * @return Collection|ImageProduct[]
     */
    public function getImageProducts(): Collection
    {
        return $this->imageProducts;
    }

    public function addImageProduct(ImageProduct $imageProduct): self
    {
        if (!$this->imageProducts->contains($imageProduct)) {
            $this->imageProducts[] = $imageProduct;
            $imageProduct->setProduct($this);
        }

        return $this;
    }

    public function removeImageProduct(ImageProduct $imageProduct): self
    {
        if ($this->imageProducts->removeElement($imageProduct)) {
            // set the owning side to null (unless already changed)
            if ($imageProduct->getProduct() === $this) {
                $imageProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        $shopCatName = $this->getShopSubCategory()->getShopCategory()->getName();
        $shopSubCatName = $this->getShopSubCategory()->getName();
        $artworkName = $this->getArtwork()->getName();

        return $shopCatName . ' - ' . $shopSubCatName . ' - ' . $artworkName;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getShopSubCategory(): ?ShopSubCategory
    {
        return $this->shopSubCategory;
    }

    public function setShopSubCategory(?ShopSubCategory $shopSubCategory): self
    {
        $this->shopSubCategory = $shopSubCategory;

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
            $purchaseItem->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchaseItems->removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getProduct() === $this) {
                $purchaseItem->setProduct(null);
            }
        }

        return $this;
    }

    public function getName(): string
    {
        return $this->__toString();
    }

    public function getFirstImage(): ImageEditorInterface
    {
        $imageProducts = $this->getImageProducts()->first();

        if (!$imageProducts) {
            return $this->getArtwork();
        }

        return $imageProducts;
    }
}
