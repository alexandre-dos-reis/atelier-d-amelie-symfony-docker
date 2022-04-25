<?php

namespace App\Doctrine\Listener;

use App\Entity\Artwork;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArtworkSlugListener
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function preFlush(Artwork $entity)
    {
        $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
    }
}
