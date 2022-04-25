<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageProductRepository;
use Symfony\Component\HttpFoundation\File\File;
use App\Service\ImageEditor\ImageEditorInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImageProductRepository::class)
 * @Vich\Uploadable
 */
class ImageProduct implements ImageEditorInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    // IMAGES PROPERTIES

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("cartService")
     */
    private $imageOriginal;

    /**
     * @Vich\UploadableField(mapping="products_images", fileNameProperty="imageOriginal")
     * @var File
     */
    private $imageOriginalFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("cartService")
     */
    private $imageWatermarked;

    /**
     * @Vich\UploadableField(mapping="products_images", fileNameProperty="imageWatermarked")
     * @var File
     */
    private $imageWatermarkedFile;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $designState = [];

    //

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disposition;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="imageProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageOriginal(): ?string
    {
        return $this->imageOriginal;
    }

    public function setImageOriginal(?string $file): self
    {
        $this->imageOriginal = $file;

        return $this;
    }

    public function setImageOriginalFile(File $file = null)
    {
        $this->imageOriginalFile = $file;
        if ($file) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageOriginalFile()
    {
        return $this->imageOriginalFile;
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

    public function getDesignState(): ?array
    {
        return $this->designState;
    }

    public function setDesignState(?array $designState): self
    {
        $this->designState = $designState;

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

    public function getDisposition(): ?int
    {
        return $this->disposition;
    }

    public function setDisposition(?int $disposition): self
    {
        $this->disposition = $disposition;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    // MAGICS

    public function __toString(): string
    {
        return $this->getImageOriginal();
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
