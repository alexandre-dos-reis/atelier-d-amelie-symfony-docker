<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArtworkRepository;
use App\Service\ImageEditor\ImageEditorInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArtworkRepository::class)
 * @Vich\Uploadable
 */
class Artwork implements ImageEditorInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cartService")
     * @Groups("adminProduct")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cartService")
     * @Groups("adminProduct")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups("cartService")
     * @Groups("adminProduct")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cartService")
     */
    private $slug;

    // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
    // // // // // // // // // // // // IMAGES & FILES // // // // // // // // // // // // //
    // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cartService")
     */
    private $imageOriginal;

    /**
     * @Vich\UploadableField(mapping="artworks_images", fileNameProperty="imageOriginal")
     * @var File
     */
    private $imageOriginalFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("cartService")
     */
    private $imageWatermarked;

    /**
     * @Vich\UploadableField(mapping="artworks_images", fileNameProperty="imageWatermarked")
     * @var File
     */
    private $imageWatermarkedFile;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $designState = [];

    // // // // // // // // // // // // // // // // // // // // // // // // // // // // // //
    // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
    // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // 


    /**
     * @ORM\Column(type="boolean")
     */
    private $showInGallery;

    /**
     * @ORM\Column(type="boolean")
     */
    private $showInPortfolio = false;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="artworks")
     * @Groups("cartService")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="artwork")
     */
    private $products;

    public function __construct()
    {
        $this->category = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

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

    public function setImageOriginalFile(File $file = null)
    {
        $this->imageOriginalFile = $file;

        if ($file) {
            $this->publishedAt = new \DateTime('now');
        }
    }

    public function getImageOriginalFile()
    {
        return $this->imageOriginalFile;
    }


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageWatermarked
     */
    public function setImageWatermarkedFile(File $file = null)
    {
        $this->imageWatermarkedFile = $file;

        if ($file) {
            $this->publishedAt = new \DateTime('now');
        }
    }

    public function getImageWatermarkedFile()
    {
        // if(is_null($this->imageWatermarked)){
        //     return $this->imageOriginalFile;
        // }
        return $this->imageWatermarkedFile;
    }

    public function getImageOriginal(): ?string
    {
        return $this->imageOriginal;
    }

    public function setImageOriginal(?string $imageOriginal): self
    {
        // We need to set the watermark and the design state to null because
        // we are updating the original with a new image
        $this->imageWatermarked = null;
        $this->designState = null;

        $this->imageOriginal = $imageOriginal;

        return $this;
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

    public function getShowInPortfolio(): ?bool
    {
        return $this->showInPortfolio;
    }

    public function setShowInPortfolio(bool $showInPortfolio): self
    {
        $this->showInPortfolio = $showInPortfolio;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

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
            $product->setArtwork($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getArtwork() === $this) {
                $product->setArtwork(null);
            }
        }

        return $this;
    }

    public function getDesignState(): ?array
    {
        return $this->designState;
    }

    public function setDesignState(?array $designState): self
    {
        $this->designState = $designState;

        return $this;
    }

    public function getImageWatermarked(): ?string
    {
        return $this->imageWatermarked;
    }

    public function setImageWatermarked(?string $imageWatermarked): self
    {
        $this->imageWatermarked = $imageWatermarked;

        return $this;
    }

    // MAGIC //

    public function __toString()
    {
        return $this->getName();
    }

    public function getImage(): string
    {
        if (is_null($this->getImageWatermarked())) {
            return $this->getImageOriginal();
        }
        return $this->getImageWatermarked();
    }

    public function getImageFile(): string
    {
        if (is_null($this->getImageWatermarked())) {
            return $this->getImageOriginalFile();
        }
        return $this->getImageWatermarkedFile();
    }
}
