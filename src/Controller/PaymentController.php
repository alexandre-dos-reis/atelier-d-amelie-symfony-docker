<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Service\Cart\CartService;
use App\Service\Purchase\PurchaseService;
use App\Service\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    /**
     * @Route("/paiement", name="payment_index")
     */
    public function index(StripeService $stripeService, PurchaseService $purchaseService): Response
    {
        $stripeService->createPaymentIntent($purchaseService->getFullTotal());

        return $this->render('payment/pay-with-stripe.html.twig', [
            'client_secret' => $stripeService->getClientSecret(),
            'public_key' => $stripeService->getPublicKey()
        ]);
    }

    /**
     * @Route("/paiement-effectue", name="payment_success")
     */
    public function success(
        Request $request,
        StripeService $stripeService,
        EntityManagerInterface $em,
        CartService $cartService,
        PurchaseService $purchaseService
    ): Response {
        // Si le panier est vide, on dégage !
        if ($cartService->isEmpty()) return $this->redirectToRoute('accueil');

        // Si le paiement n'est pas valide, on dégage ! Inutile ?
        if (!$stripeService->isPaymentSucceeded($request)) return $this->redirectToRoute('accueil');

        // Vérifier que ce endpoint est bien appelé par Stripe ou utiliser les webhooks.

        // Create the purchase
        $em->persist($purchaseService->createPurchase($request));
        $em->flush();

        $cartService->emptyCart();

        return $this->render('payment/success.html.twig', []);
    }

    /**
     * @Route("/paiement/confirmation", name="payment_confirmation", methods="POST")
     */
    public function confirmPayment(Request $request, StripeService $stripeService, EntityManagerInterface $em, PurchaseService $purchaseService): Response
    {
        // https://dashboard.stripe.com/test/webhooks

        $purchase = $stripeService->getPurchaseByStripeId($request);

        if (!$purchase) return $this->json("Purchase not found.", 404);

        if ($purchase->getStatus() !== Purchase::PAIEMENT_EN_ATTENTE) {
            return $this->json("This purchase isn't waiting for payment.", 403);
        }

        $purchase->setStatus(Purchase::PREPARATION_EN_COURS);

        // A tester !
        $purchaseService->decreaseStockByQuantityBought($purchase);

        // TODO: Envoyer un email au main user 

        $em->persist($purchase);
        $em->flush();

        return $this->json('OK', 200);
    }
}
