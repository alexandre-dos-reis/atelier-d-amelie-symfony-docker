<?php

namespace App\Controller\Admin;

use App\Entity\Artwork;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ShopCategory;
use App\Entity\ShopSubCategory;
use App\Entity\Purchase;
use App\Entity\ShippingCost;
use App\Entity\AppSettings;
use App\Entity\ImageProduct;
use App\Repository\PurchaseRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $purchaseRepo;

    public function __construct(PurchaseRepository $purchaseRepo)
    {
        $this->purchaseRepo = $purchaseRepo;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // $purchasesAwaiting = $purchaseRepo->findBy([
        //     'status' => Purchase::STATUS_ARRAY[0]
        // ]);

        $purchasesAwaiting = $this->purchaseRepo->findBy([
            'status' => Purchase::PREPARATION_EN_COURS
        ]);

        return $this->render('admin/dashboard.html.twig', [
            'purchases' => $purchasesAwaiting
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle("Administration");
    }

    public function configureMenuItems(): iterable
    {
        // https://fontawesome.com/v4/icons/

        // yield MenuItem::section('Général');
        yield MenuItem::linkToUrl("Retour vers le site", 'fa fa-step-backward', '/');
        yield MenuItem::linkToRoute('Statistiques', 'fa fa-line-chart', 'stats');


        yield MenuItem::section('Galerie');
        yield MenuItem::linkToCrud('Oeuvres', 'fa fa-palette', Artwork::class)
            ->setDefaultSort(['publishedAt' => 'DESC']);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-tags', Category::class)
            ->setDefaultSort(['disposition' => 'ASC']);

        // yield MenuItem::linkToRoute('Voir la galerie', 'fa fa-sign-out', 'galerie_all');

        yield MenuItem::section('Boutique');
        yield MenuItem::linkToCrud('Produits', 'fa fa-gift', Product::class)
            ->setDefaultSort(['updatedAt' => 'DESC']);
        yield MenuItem::linkToCrud('Images', 'fa fa-picture-o', ImageProduct::class)
            ->setDefaultSort(['product' => 'ASC']);
        yield MenuItem::linkToCrud('Sous-catégories', 'fa fa-tags', ShopSubCategory::class)
        ->setDefaultSort(['shopCategory.name' => 'ASC']);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-tag', ShopCategory::class)
            ->setDefaultSort(['disposition' => 'ASC']);

        yield MenuItem::section('Ventes');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-credit-card-alt', Purchase::class)
            ->setDefaultSort(['purchasedAt' => 'DESC']);
        yield MenuItem::linkToUrl('Paiements Stripe', 'fa fa-eur', 'https://dashboard.stripe.com/test/payments?status%5B%5D=successful');

        yield MenuItem::section('Paramètres');
        //yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Frais de port', 'fa fa-truck', ShippingCost::class)
            ->setDefaultSort(['max' => 'ASC']);
        yield MenuItem::linkToCrud('Configuration', 'fa fa-cog', AppSettings::class);


        // yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addWebpackEncoreEntry('admin-app');
    }
}
