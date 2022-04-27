<?php

namespace App\Controller\Admin;

use App\Entity\Artwork;
use App\Entity\ImageProduct;
use App\Repository\ArtworkRepository;
use App\Repository\ImageProductRepository;
use App\Service\ImageEditor\ImageEditorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtworkEditorController extends AbstractController
{
    private AdminUrlGenerator $adminUrlGenerator;
    private ArtworkRepository $artworkRepo;
    private ImageProductRepository $imageproductRepo;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, ArtworkRepository $artworkRepo, ImageProductRepository $imageproductRepo)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->artworkRepo = $artworkRepo;
        $this->imageproductRepo = $imageproductRepo;
    }

    /**
     * @Route("/admin/image-editor/{entityName}/{id}", name="imageEditor")
     */
    public function index(string $entityName, int $id): Response
    {
        $entity = $this->getEntity($entityName, $id);

        if (!$entity instanceof ImageEditorInterface) {
            throw new NotFoundException("Cette entité n'existe pas ou n'implémente pas l'interface ImageEditorInterface !", 404);
        }

        $getBackUrl = $this->adminUrlGenerator
            ->setController($this->getEntityCrudControllerClass($entity))
            ->setAction(Action::EDIT)
            ->setEntityId($this->getEntityIdForCrudController($entity))
            ->generateUrl();

        return $this->render('admin/imageEditor.html.twig', [
            'entity' => $entity,
            'entityName' => $entityName,
            'getBackUrl' => $getBackUrl
        ]);
    }

    /**
     * @Route("/admin/image-editor/update/{entityName}/{id}", name="imageEditorUpdate", methods="POST")
     */
    public function update(Request $request, string $entityName, int $id, EntityManagerInterface $em, Filesystem $filesystem): Response
    {
        // TODO : Supprimer la miniature de Liip Imagine bundle si elle existe

        $entity = $this->getEntity($entityName, $id);

        $this->json("Cette entité n'existe pas !", 404);

        // Delete the previous image if exist...
        if ($entity->getImageWatermarked()) {
            $fileNameToDelete = $this->getImagePath($entity) . $entity->getImageWatermarked();
            $filesystem->remove($fileNameToDelete);
        }

        // Save the new content to Entity : Design state and watermarked image name
        $data = json_decode($request->getContent(), true);
        $entity->setDesignState($data['designState']);
        $entity->setImageWatermarked("watermarked-" . $entity->getImageOriginal());

        // Save the image to file
        $base64image = $data['editedImageObject']['imageBase64'];
        $decorder = new Base64ImageDecoder($base64image, ['jpeg']);
        $fileNameToCreate = $this->getImagePath($entity) . $entity->getImageWatermarked();
        $filesystem->appendToFile($fileNameToCreate, $decorder->getDecodedContent());

        // Save to database
        $em->persist($entity);
        $em->flush();

        return $this->json("The image was updated.", 200);
    }

    /**
     * @Route("/admin/oeuvre-edition/reset/{entityName}/{id}", name="imageEditorReset", methods="POST")
     */
    public function reset(string $entityName, int $id, EntityManagerInterface $em, Filesystem $filesystem, CacheManager $cacheManager): Response
    {
        // TODO : Supprimer la miniature de Liip Imagine bundle si elle existe
        $entity = $this->getEntity($entityName, $id);

        $this->json("Cette entité n'existe pas !", 404);

        // To be sure that we are deleting the right file
        if ($entity->getImageWatermarked()) {
            $filesystem->remove($this->getImagePath($entity) . $entity->getImageWatermarked());

            if ($entity instanceof ImageProduct) {
                dump($entity);
                $cacheManager->remove('uploads/products/' . $entity->getImageWatermarked());
            }
            if ($entity instanceof Artwork) {
                $cacheManager->remove('uploads/artworks/' . $entity->getImageWatermarked());
            }
        }

        $entity->setDesignState(null);
        $entity->setImageWatermarked(null);

        $em->persist($entity);
        $em->flush();

        return $this->json("The image was reseted.", 200);
    }

    /**
     * @Route("/admin/oeuvre-edition/getDesignState/{entityName}/{id}", name="imageEditorGetDesignState")
     */
    public function getDesignState(string $entityName, int $id): Response
    {
        $entity = $this->getEntity($entityName, $id);

        if (!$entity) throw new NotFoundException("Cette entité n'existe pas !", 404);

        return $this->json($entity->getDesignState(), 200);
    }

    private function getEntity(string $entityName, int $id): ?ImageEditorInterface
    {
        $entityRepository = strtolower($entityName) . "Repo";
        return $this->$entityRepository->find($id);
    }

    private function getEntityCrudControllerClass(ImageEditorInterface $entity): ?string
    {
        if ($entity instanceof Artwork) {
            return ArtworkCrudController::class;
        }

        if ($entity instanceof ImageProduct) {
            return ProductCrudController::class;
        }
    }

    private function getEntityIdForCrudController(ImageEditorInterface $entity): ?int
    {
        if ($entity instanceof Artwork) {
            return $entity->getId();
        }

        if ($entity instanceof ImageProduct) {
            return $entity->getProduct()->getId();
        }
    }

    private function getImagePath(ImageEditorInterface $entity): string
    {
        $projectPublicPath = $this->getParameter("kernel.project_dir") . "/public";

        if ($entity instanceof Artwork) {
            $entityPath = $this->getParameter('app.path.artworks_images');
        }

        if ($entity instanceof ImageProduct) {
            $entityPath = $this->getParameter('app.path.products_images');
        }

        return $projectPublicPath . $entityPath . "/";
    }
}
