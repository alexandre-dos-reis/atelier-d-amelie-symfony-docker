<?php

namespace App\Service\Image;

use App\Entity\Artwork;
use App\Entity\Product;
use App\Entity\ImageProduct;
use App\Service\HttpToHttps\HttpToHttpsService;
use Liip\ImagineBundle\Service\FilterService;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageService
{
    private UploaderHelper $uploaderHelper;
    private FilterService $filterService;
    private static ?HttpToHttpsService $httpToHttpsService;

    public function __construct(
        UploaderHelper $uploaderHelper,
        FilterService $filterService,
        HttpToHttpsService $httpToHttpsService
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->filterService = $filterService;
        self::$httpToHttpsService = $httpToHttpsService;
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
            $assetUrl = $this->uploaderHelper->asset($product->getArtwork(), $imageFile, Artwork::class);
            array_push($srcImages, self::$httpToHttpsService->convert($assetUrl));
        } else {
            foreach ($product->getImageProducts() as $ip) {
                $imageFile = $ip->getImageWatermarked() ? 'imageWatermarkedFile' : 'imageOriginalFile';
                $assetUrl = $this->uploaderHelper->asset($ip, $imageFile, ImageProduct::class);
                array_push($srcImages, self::$httpToHttpsService->convert($assetUrl));
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
