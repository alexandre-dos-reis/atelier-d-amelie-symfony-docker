<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    protected $cartService;
    protected $productRepository;

    public function __construct(CartService $cartService, ProductRepository $productRepository)
    {
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/panier/{id<\d+>}/{qty<\d+>}", name="cart_set")
     */
    public function set(int $id, int $qty): Response
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        if (!$product || $qty > $product->getStock()) {
            return $this->json('Ce produit est inexistant ou la quantité demandé n\'est pas bonne !', 404);
        }

        $this->cartService->addOrUpdate($id, $qty);

        return $this->json(
            [
                // 'detailedCartItems' => $this->cartService->getDetailedCartItems(),
                // 'total' => $this->cartService->getTotal(),
                'numberOfProducts' => $this->cartService->countProducts(),
                'product' => $product
            ],
            200,
            [],
            ['groups' => 'cartService']
        );
    }

    /**
     * @Route("/panier", name="cart_show")
     */
    public function show(): Response
    {
        return $this->json(
            [
                'detailedCartItems' => $this->cartService->getDetailedCartItems(),
                'total' => $this->cartService->getTotal()
            ],
            200,
            [],
            ['groups' => 'cartService']
        );
    }

    /**
     * @Route("/panier/vider", name="cart_empty")
     */
    public function empty()
    {
        $this->cartService->emptyCart();

        return $this->json(
            [
                'message' => 'Le panier a été vidé.'
            ],
            200
        );
    }

    /**
     * @Route("/panier/supprimer/{id<\d+>}", name="cart_delete")
     */
    public function delete($id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) return $this->json("Ce produit n'existe pas !", 404);

        $this->cartService->delete($id);

        return $this->json(
            [
                'numberOfProducts' => $this->cartService->countProducts(),
                'message' => "Le produit n°$id a bien été supprimé du panier."
            ],
            200,
            [],
            ['groups' => 'cartService']
        );
    }
}
