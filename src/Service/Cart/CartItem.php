<?php

namespace App\Service\Cart;

use App\Entity\Product;
use Symfony\Component\Serializer\Annotation\Groups;

class CartItem
{

    /**
     * @Groups("cartService")
     */
    public $product;

    /**
     * @Groups("cartService")
     */
    public $qty;

    public function __construct(Product $product, int $qty)
    {
        $this->product = $product;
        $this->qty = $qty;
    }

    public function getTotal(): int
    {
        return $this->product->getPrice() * $this->qty;
    }
}
