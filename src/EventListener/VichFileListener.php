<?php

namespace App\EventListener;

use App\Entity\Artwork;
use App\Entity\ImageProduct;
use App\Service\ImageEditor\ImageEditorInterface;
use Vich\UploaderBundle\Event\Event;
use Symfony\Component\Filesystem\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class VichFileListener
{
    protected CacheManager $cacheManager;
    protected Filesystem $filesystem;

    public function __construct(CacheManager $cacheManager, Filesystem $filesystem)
    {
        $this->cacheManager = $cacheManager;
        $this->filesystem = $filesystem;
    }

    public function onVichUploaderPreRemove(Event $event): void
    {
        $entity = $event->getObject();

        if ($entity instanceof ImageEditorInterface) {
            // We don't write code to delete the originale file because, VichUploader does it.
            $this->deleteWatermarkedFile($entity, $event);
            $this->deleteLiipImagineFiles($entity);
        }
    }

    public function onVichUploaderPreUpload(Event $event)
    {
        $entity = $event->getObject();

        if ($entity instanceof ImageEditorInterface) {

            $this->deleteOriginalFile($entity, $event);
            $this->deleteWatermarkedFile($entity, $event);
            $this->deleteLiipImagineFiles($entity);
        }
    }

    private function deleteLiipImagineFiles(ImageEditorInterface $entity): void
    {
        if ($entity->getImageOriginal()) {
            $this->cacheManager->remove(
                $this->getSubFolderPath($entity) . $entity->getImageOriginal()
            );
        }
        if ($entity->getImageWatermarked()) {
            $this->cacheManager->remove(
                $this->getSubFolderPath($entity) . $entity->getImageWatermarked()
            );
        }
    }

    private function getSubFolderPath(ImageEditorInterface $entity): ?string
    {
        if ($entity instanceof Artwork) return 'uploads/artworks/';
        if ($entity instanceof ImageProduct) return 'uploads/products/';
    }

    private function getFolderPath(Event $event): string
    {
        return $event->getMapping()->getUploadDestination() . "/";
    }

    private function deleteWatermarkedFile(ImageEditorInterface $entity, Event $event): void
    {
        if ($entity->getImageWatermarked()) {
            $this->filesystem->remove($this->getFolderPath($event) . $entity->getImageWatermarked());
        }
    }

    private function deleteOriginalFile(ImageEditorInterface $entity, Event $event): void
    {
        if ($entity->getImageOriginal()) {
            $this->filesystem->remove($this->getFolderPath($event) . $entity->getImageOriginal());
        }
    }
}
