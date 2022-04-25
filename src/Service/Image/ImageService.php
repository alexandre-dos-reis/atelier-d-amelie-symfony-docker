<?php

namespace App\Service\Image;

use App\Entity\Artwork;
use App\Entity\Product;
use App\Entity\ImageProduct;
use Liip\ImagineBundle\Service\FilterService;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageService
{
    private UploaderHelper $uploaderHelper;
    private FilterService $filterService;

    public function __construct(UploaderHelper $uploaderHelper, FilterService $filterService)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->filterService = $filterService;
    }

    public function getImageProductsForReact(
        Product $product,
        string $liipImagineThumbnailFilterName,
        string $liipImagineOriginalFilterName
    ): array {

        $srcImages = [];

        if ($product->getImageProducts()->count() === 0) {
            // No imageProduct for this product, we get the artwork image instead.
            $imageFile = $product->getArtwork()->getImageWatermarked() ? 'imageWatermarkedFile' : 'imageOriginalFile';
            array_push($srcImages, $this->uploaderHelper->asset($product->getArtwork(), $imageFile, Artwork::class));
        } else {
            foreach ($product->getImageProducts() as $ip) {
                $imageFile = $ip->getImageWatermarked() ? 'imageWatermarkedFile' : 'imageOriginalFile';
                array_push($srcImages, $this->uploaderHelper->asset($ip, $imageFile, ImageProduct::class));
            }
        }

        // Liip Imagine Images
        $filterSrcImages = [];
        foreach ($srcImages as $si) {
            array_push($filterSrcImages, [
                'original' => $this->filterService->getUrlOfFilteredImage($si, $liipImagineOriginalFilterName),
                'thumbnail' => $this->filterService->getUrlOfFilteredImage($si, $liipImagineThumbnailFilterName)
            ]);
        }

        return $filterSrcImages;
    }
}
