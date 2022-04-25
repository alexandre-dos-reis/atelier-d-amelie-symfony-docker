<?php

namespace App\Controller;

use Stripe\StripeClient;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Service\Cart\CartItem;
use App\Service\Cart\CartService;
use App\Form\PurchaseAddressType;
use App\Repository\PurchaseRepository;
use App\Repository\AppSettingsRepository;
use App\Service\PurchaseAddress\PurchaseAddressService;
use App\Service\ShippingCost\ShippingCostService;
use App\Service\Stripe\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{
    public $appSettingsRepo;
    public $purchaseAddressService;
    public $cartService;

    public function __construct(
        AppSettingsRepository $appSettingsRepo,
        PurchaseAddressService $purchaseAddressService,
        CartService $cartService
    ) {
        $this->appSettingsRepo = $appSettingsRepo;
        $this->purchaseAddressService = $purchaseAddressService;
        $this->cartService = $cartService;
    }

    /**
     * Formulaire de coordonnées à stocker dans la session.
     * 
     * @Route("/commande/informations", name="purchase_info")
     */
    public function info(Request $request): Response
    {
        // Si le panier est vide, on dégage !
        if ($this->cartService->isEmpty()) return $this->redirectToRoute('accueil');

        $form = $this->createForm(PurchaseAddressType::class, $this->purchaseAddressService->get());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $this->purchaseAddressService->set($data);

            return $this->redirectToRoute('purchase_payment', [
                'cgvBool' => '0',
            ]);
        }

        return $this->renderForm('purchase/info.html.twig', [
            'formView' => $form
        ]);
    }

    /**
     * Récapitulatif de la commande avant paiement via Stripe
     * 
     * @Route("/commande/confirmation/cgv={cgvBool}", name="purchase_payment")
     */
    public function purchaseConfirmation(bool $cgvBool, ShippingCostService $shippingCostService): Response
    {
        // Si le panier est vide, on dégage !
        if ($this->cartService->isEmpty()) return $this->redirectToRoute('accueil');

        $total = $this->cartService->getTotal();
        $shippingCost = $shippingCostService->getShippingCost($this->cartService);

        return $this->render('purchase/confirmation.html.twig', [
            'purchaseAddresses' => $this->purchaseAddressService->get(),
            'cart' => $this->cartService->getDetailedCartItems(),
            'insuranceCost' => $shippingCost->getInsurance(),
            'weightCost' => $shippingCost->getWeightCost(),
            'totalProducts' => $total,
            'totalOfTotal' => $total + $shippingCost->getInsurance() + $shippingCost->getWeightCost(),
            'cgvBool' => $cgvBool ? true : false,
        ]);
    }

    /**
     * @Route("/cgv", name="cgv")
     */
    public function cgv(): Response
    {
        $cgv = $this->appSettingsRepo->findAll()[0]->getCgv();

        return $this->render('purchase/cgv.html.twig', [
            'cgv' => $cgv
        ]);
    }
}
