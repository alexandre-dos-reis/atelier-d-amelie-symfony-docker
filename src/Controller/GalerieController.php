<?php

namespace App\Controller;

use App\Repository\ArtworkRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GalerieController extends AbstractController
{
    private $artworkRepo;
    private $categoryRepo;
    private $categories;

    public function __construct(ArtworkRepository $artworkRepo, CategoryRepository $categoryRepo)
    {
        $this->artworkRepo = $artworkRepo;
        $this->categoryRepo = $categoryRepo;
        $this->categories = $this->categoryRepo->findBy(
            ['showInGallery' => TRUE],
            ['disposition' => 'ASC']
        );
    }

    /**
     * @Route("/galerie", name="galerie_all")
     */
    public function all(): Response
    {
        return $this->render('galerie/index.html.twig', [
            'categories' => $this->categories,
            'artworks' => $this->artworkRepo->findBy(
                ['showInGallery' => true],
                ['publishedAt' => 'DESC']
            ),
            'categorySelected' => 'all'
        ]);
    }

    /**
     * @Route("/galerie/{slug}", name="galerie_categorie")
     */
    public function category($slug): Response
    {
        $category = $this->categoryRepo->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) throw $this->createNotFoundException("La catégorie demandée n'existe pas !");

        return $this->render('galerie/index.html.twig', [
            'categories' => $this->categories,
            'artworks' => $this->artworkRepo->findAllGalleryByCategory($category),
            'categorySelected' => $category->getSlug()
        ]);
    }

    /**
     * @Route("/artwork/{id}", name="api_artwork")
     */
    public function artwork($id): Response
    {
        $artwork = $this->artworkRepo->find($id);

        if (!$artwork) return $this->json("Cette oeuvre n'existe pas !", 404);

        return $this->json($artwork, 200, [], [
            'groups' => 'adminProduct'
        ]);
    }
}
