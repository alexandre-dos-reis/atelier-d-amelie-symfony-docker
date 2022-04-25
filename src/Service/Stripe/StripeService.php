<?php

namespace App\Service\Stripe;

use Stripe\Event;
use Stripe\Webhook;
use App\Entity\Purchase;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use App\Repository\PurchaseRepository;
use Symfony\Component\HttpFoundation\Request;

class StripeService
{
    private PaymentIntent $paymentIntent;
    private PurchaseRepository $purchaseRepo;
    private string $secretKey;
    private string $publicKey;
    private string $confirmEndPointKey;

    public function __construct(string $secretKey, string $publicKey, string $confirmEndPointKey, PurchaseRepository $purchaseRepo)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
        $this->confirmEndPointKey = $confirmEndPointKey;
        $this->purchaseRepo = $purchaseRepo;
    }

    public function createPaymentIntent(int $amount, string $currency = 'eur'): void
    {
        $this->paymentIntent = (new StripeClient($this->secretKey))->paymentIntents->create([
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }

    public function getClientSecret(): ?string
    {
        return $this->paymentIntent->client_secret;
    }

    public function getPublicKey(): string
    {
        return  $this->publicKey;
    }

    public function isPaymentSucceeded(Request $request): bool
    {
        return $request->query->get('redirect_status') !== 'succeeded' ? false : true;
    }

    public function getPurchaseByStripeId(Request $request): ?Purchase
    {
        $event = $this->getWebhookEvent($request);
        return $this->purchaseRepo->findOneBy(['stripeId' => $event->data->object->id]);
    }

    private function getWebhookEvent(Request $request): ?Event
    {
        return Webhook::constructEvent(
            $request->getContent(),
            $request->server->get('HTTP_STRIPE_SIGNATURE'),
            $this->confirmEndPointKey
        );
    }
}
