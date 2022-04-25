<?php

namespace App\Service\ImageEditor;

use Symfony\Component\HttpFoundation\File\File;

interface ImageEditorInterface
{
    public function getId(): ?int;
    public function setImageOriginalFile(File $file = null);
    public function getImageOriginalFile();
    public function setImageWatermarkedFile(File $file = null);
    public function getImageWatermarkedFile();
    public function getImageOriginal(): ?string;
    public function setImageOriginal(?string $imageOriginal): self;
    public function getDesignState(): ?array;
    public function setDesignState(?array $designState): self;
    public function getImageWatermarked(): ?string;
    public function setImageWatermarked(?string $imageWatermarked): self;
    public function getImage(): string;
    public function getImageFile(): string;
}
