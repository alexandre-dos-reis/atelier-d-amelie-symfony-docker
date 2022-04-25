<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;
use App\Entity\PurchaseAddress;
use App\Entity\PurchaseItem;
use App\Entity\ShippingCost;
use App\Service\Cart\CartService;
use App\Service\PurchaseAddress\PurchaseAddressInterface;
use App\Service\PurchaseAddress\PurchaseAddressService;
use App\Service\ShippingCost\ShippingCostService;
use App\Service\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PurchaseService
{
    private CartService $cartService;
    private ShippingCost $shippingCost;
    private PurchaseAddressInterface $purchaseAddress;
    private EntityManagerInterface $em;

    public function __construct(
        CartService $cartService,
        ShippingCostService $shippingCostService,
        PurchaseAddressService $purchaseAddressService,
        EntityManagerInterface $em
    ) {
        $this->cartService = $cartService;
        $this->shippingCost = $shippingCostService->getShippingCost($cartService);
        $this->purchaseAddress = $purchaseAddressService->get();
        $this->em = $em;
    }

    public function getFullTotal(): int
    {
        return $this->cartService->getTotal()
            + $this->shippingCost->getInsurance()
            + $this->shippingCost->getWeightCost();
    }

    public function createPurchase(request $request): Purchase
    {

        $purchase = new Purchase();

        $purchase
            ->setUuid()
            ->setPurchasedAt()
            ->setStripeId($request->query->get('payment_intent'))
            ->setTotal($this->cartService->getTotal())
            ->setInsuranceCost($this->shippingCost->getInsurance())
            ->setWeightCost($this->shippingCost->getWeightCost())
            ->setStatus(Purchase::PAIEMENT_EN_ATTENTE)
            ->setEmail($this->purchaseAddress->getEmail());

        // Add addresses
        foreach ($this->getPurchaseAddresses() as $purchaseAddress) {
            $purchase->addPurchaseAddress($purchaseAddress);
        }

        // Add 
        foreach ($this->getPurchaseItems() as $purchaseItem) {
            $purchase->addPurchaseItem($purchaseItem);
        }

        return $purchase;
    }

    /**
     * @return PurchaseAddress[]
     */
    private function getPurchaseAddresses(): array
    {
        if ($this->purchaseAddress->getHasBillingAddress()) {
            $billingAddress = (new PurchaseAddress())
                ->setAddress($this->purchaseAddress->getFirstAddressAddress())
                ->setCity($this->purchaseAddress->getFirstAddressCity())
                ->setCountry($this->purchaseAddress->getFirstAddressCountry())
                ->setFullname($this->purchaseAddress->getFirstAddressFullname())
                ->setPhone($this->purchaseAddress->getFirstAddressPhone())
                ->setPostalCode($this->purchaseAddress->getFirstAddressPostalCode())
                ->setType(PurchaseAddress::ADDR_TYPE_BILLING);

            $deliveryAddress = (new PurchaseAddress())
                ->setAddress($this->purchaseAddress->getSecondAddressAddress())
                ->setCity($this->purchaseAddress->getSecondAddressCity())
                ->setCountry($this->purchaseAddress->getSecondAddressCountry())
                ->setFullname($this->purchaseAddress->getSecondAddressFullname())
                ->setPostalCode($this->purchaseAddress->getFirstAddressPostalCode())
                ->setType(PurchaseAddress::ADDR_TYPE_DELIVERY);

            return [$billingAddress, $deliveryAddress];
        } else {
            $purchaseAddress = (new PurchaseAddress())
                ->setAddress($this->purchaseAddress->getFirstAddressAddress())
                ->setCity($this->purchaseAddress->getFirstAddressCity())
                ->setCountry($this->purchaseAddress->getFirstAddressCountry())
                ->setFullname($this->purchaseAddress->getFirstAddressFullname())
                ->setPhone($this->purchaseAddress->getFirstAddressPhone())
                ->setPostalCode($this->purchaseAddress->getFirstAddressPostalCode())
                ->setType(PurchaseAddress::ADDR_TYPE_DELIVERY_AND_BILLING);

            return [$purchaseAddress];
        }
    }

    /**
     * @return PurchaseItem[]
     */
    private function getPurchaseItems(): array
    {
        $purchaseItems = [];

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {

            $purchaseItem = new PurchaseItem;

            $purchaseItem
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQty($cartItem->qty);

            $purchaseItems[] = $purchaseItem;

            return $purchaseItems;
        }
    }

    public function decreaseStockByQuantityBought(Purchase $purchase): void
    {
        foreach ($purchase->getPurchaseItems() as $pi) {
            $quantityBought = $pi->getQty();
            $product = $pi->getProduct();
            $product->setStock($product->getStock() - $quantityBought);
            $this->em->persist($product);
        }
        $this->em->flush();
    }
}
