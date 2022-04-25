<?php

namespace App\Service\ShippingCost;

use App\Entity\ShippingCost;
use App\Repository\ShippingCostRepository;
use App\Service\Cart\CartService;

class ShippingCostService
{
    protected $shippingCostRepo;

    public function __construct(ShippingCostRepository $shippingCostRepo)
    {
        $this->shippingCostRepo = $shippingCostRepo;
    }

    public function getShippingCost(CartService $cartService): ShippingCost
    {
        $shippingCosts = $this->shippingCostRepo->findBy([], ['max' => 'ASC']);

        foreach ($shippingCosts as $sc) {
            if ($cartService->getTotal() <= $sc->getMax()) return $sc;
        }

        // Return last element
        return $shippingCosts[count($shippingCosts) - 1];
    }
}
