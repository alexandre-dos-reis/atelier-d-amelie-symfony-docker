<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;

class ProductUpdatedAtListener
{
    public function preFlush(Product $entity)
    {
        $entity->setUpdatedAt(new \DateTime('now'));
    }
}
