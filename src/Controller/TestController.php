<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private AdminUrlGenerator $adminUrlGenerator;
    private ArtworkRepository $artworkRepo;
    private ProductRepository $productRepo;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, ArtworkRepository $artworkRepo, ProductRepository $productRepo)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->artworkRepo = $artworkRepo;
        $this->productRepo = $productRepo;
    }
}
