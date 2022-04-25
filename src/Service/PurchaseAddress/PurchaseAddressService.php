<?php

namespace App\Service\PurchaseAddress;

use App\Service\PurchaseAddress\PurchaseAddressInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PurchaseAddressService
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function set(PurchaseAddressInterface $purchaseAddressInterface): void
    {
        $addressesPrime = $this->session->get('purchaseAddressInterface', $purchaseAddressInterface);
        $this->session->set('purchaseAddressInterface', $addressesPrime);
    }

    public function get(): PurchaseAddressInterface
    {
        return $this->session->get('purchaseAddressInterface', new PurchaseAddressInterface());
    }

    public function empty(): void
    {
        $this->session->set('purchaseAddressInterface', null);
    }
}
