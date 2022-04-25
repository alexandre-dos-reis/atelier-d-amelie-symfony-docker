<?php

namespace App\Controller;

use App\Entity\ImageProduct;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\ImageProductRepository;
use App\Repository\ShopCategoryRepository;
use App\Repository\ShopSubCategoryRepository;
use App\Service\Image\ImageService;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController
{
    private $productRepository;
    private $shopCategoryRepository;
    private $shopSubCategoryRepository;
    private $imageProductRepository;

    public function __construct(
        ProductRepository $productRepository,
        ImageProductRepository $imageProductRepository,
        ShopCategoryRepository $shopCategoryRepository,
        ShopSubCategoryRepository $shopSubCategoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->imageProductRepository = $imageProductRepository;
        $this->shopCategoryRepository = $shopCategoryRepository;
        $this->shopSubCategoryRepository = $shopSubCategoryRepository;
    }

    /**
     * @Route("/boutique/commande", name="boutique_commande")
     */
    public function commande(): Response
    {
        return $this->render('boutique/custom.html.twig');
    }

    /**
     * @Route("/boutique/{cat_slug}", name="boutique_categorie")
     */
    public function categorie($cat_slug): Response
    {
        $shopCat = $this->shopCategoryRepository->findOneBy([
            'slug' => $cat_slug,
        ]);

        if (!$shopCat) throw $this->createNotFoundException("Cette catégorie n'existe pas !");

        $shopSubCats = $this->shopSubCategoryRepository->findBy(
            ['shopCategory' => $shopCat],
            ['disposition' => 'ASC'],
        );

        // Remove shop sub cat that have no products...
        // convert this to DQL...
        foreach ($shopSubCats as $i => $sc) {

            $products = $sc->getProducts();

            foreach($products as $j => $p){
                if(!$p->getForSale()){
                    unset($products[$j]);
                }
            }

            if ($products->count() === 0) {
                unset($shopSubCats[$i]);
            }
        }

        return $this->render('boutique/category.html.twig', [
            'shopCat' => $shopCat,
            'shopSubCats' => $shopSubCats,
        ]);
    }

    /**
     * @Route("/boutique/{cat_slug}/{sub_cat_slug}/{p_slug}_{p_id<\d+>}", name="boutique_produit")
     */
    public function produit($cat_slug, $sub_cat_slug, $p_id): Response
    {
        $product = $this->productRepository->findOneBy([
            'id' => $p_id,
            'forSale' => true
        ]);
        if (!$product) throw $this->createNotFoundException("Le produit demandé n'existe pas !");

        $shopCategory = $this->shopCategoryRepository->findOneBy([
            'slug' => $cat_slug
        ]);
        if (!$shopCategory) throw $this->createNotFoundException("La catégorie demandée n'existe pas !");

        $shopSubCategory = $this->shopSubCategoryRepository->findOneBy([
            'slug' => $sub_cat_slug
        ]);
        if (!$shopSubCategory) throw $this->createNotFoundException("La catégorie demandée n'existe pas !");

        $imageProducts = $this->imageProductRepository->findBy(
            ['product' => $product],
            ['disposition' => 'ASC']
        );

        return $this->render('boutique/product.html.twig', [
            'product' => $product,
            'imageProducts' => $imageProducts,
            'shopCategory' => $shopCategory,
            'shopSubCategory' => $shopSubCategory,
        ]);
    }

    /**
     * @Route("/image-product/{id<\d+>}", name="boutique_image_product")
     */
    public function getProductImage(int $id, ProductRepository $productRepo, ImageService $imageService): Response
    {
        $product = $productRepo->find($id);

        if (!$product) return $this->json('Product not found', 404);

        return $this->json(
            $imageService->getImageProductsForReact($product, 'boutique_mini', 'boutique_full')
        );
    }
}
