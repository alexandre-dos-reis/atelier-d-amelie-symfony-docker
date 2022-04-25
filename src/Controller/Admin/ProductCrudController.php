<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ImageProductType;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    private string $productsFolder;
    private string $artworksFolder;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(string $productsFolder, string $artworksFolder, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->productsFolder = $productsFolder;
        $this->artworksFolder = $artworksFolder;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = (new EaDesignService())->addIconsToActions($actions);

        $ProductAction = Action::new('productAction', "Accèder à la page produit", 'fa fa-step-backward')
            ->linkToRoute('boutique_produit', fn (Product $entity) => [
                'cat_slug' => $entity->getShopSubCategory()->getShopCategory()->getSlug(),
                'sub_cat_slug' => $entity->getShopSubCategory()->getSlug(),
                'p_slug' => $entity->getArtwork()->getSlug(),
                'p_id' => $entity->getId()
            ]);

        $actions
            ->add(Crud::PAGE_INDEX, $ProductAction)
            ->add(Crud::PAGE_EDIT, $ProductAction);

        return $actions->reorder(Crud::PAGE_INDEX, [
            Action::EDIT, 'productAction', Action::DELETE
        ]);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un produit')
            ->setEntityLabelInPlural('Liste des produits')
            ->setDateFormat('dd MMMM yyyy')
            ->setSearchFields(['shopSubCategory.shopCategory'])
            ->setFormThemes([
                'admin/_shopCategory_edit.html.twig',
                'admin/_imageProducts.html.twig',
                'admin/_productDescription.html.twig',
                'admin/_productArtwork.html.twig',
                '@EasyAdmin/crud/form_theme.html.twig'
            ]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->new(DateTimeFilter::new('updatedAt', 'Date de modification'))
            ->add('artwork')
            ->add('description')
            ->add('height')
            ->add('width')
            ->add('stock')
            ->add('price')
            ->add('shopSubCategory')
            ->add('forSale');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('updatedAt', 'Date de modification')->onlyOnIndex(),

            BooleanField::new('forSale', 'En vente ?'),

            TextField::new('artwork', 'Oeuvre')->formatValue(function ($value, $entity) {
                $href = $this->adminUrlGenerator
                    ->setController(ArtworkCrudController::class)
                    ->setAction('edit')
                    ->setEntityId($entity->getArtwork()->getId())
                    ->set('menuIndex', '3')
                    ->set('sort', [
                        'publishedAt' => 'DESC'
                    ])
                    ->generateUrl();

                return '<a href="' . $href . '">' . $value . '</a>';
            })->onlyOnIndex(),

            AssociationField::new('artwork', 'Oeuvre')->hideOnIndex()->setFormTypeOptions([
                'block_name' => 'custom_artwork',
            ]),
            // ImageField::new('artwork.filepathFull', 'Oeuvre')
            //     ->setBasePath('/uploads/artworks/')
            //     ->onlyOnIndex(),
            AssociationField::new('imageProducts', 'Produit')->formatValue(function ($value, $entity) {

                /** @var Product $entity */
                
                if($entity->getImageProducts()->count() === 0){
                    $image = $this->artworksFolder . "/" . $entity->getArtwork()->getImage();
                } else {
                    $image = $this->productsFolder . "/" . $entity->getImageProducts()->first();
                }

                return '<img style="height: 40px; width: auto;" src="' . $image . '"/>';
            })->onlyOnIndex(),

            TextareaField::new('description', 'Description')->hideOnIndex()->setFormTypeOptions([
                'block_name' => 'custom_description',
            ]),
            IntegerField::new('height', 'Hauteur en mm')->hideOnIndex(),
            IntegerField::new('width', 'Longueur en mm')->hideOnIndex(),
            IntegerField::new('stock', 'Stock')->formatValue(function ($value) {
                if ($value === 0) {
                    return '<span class="badge badge-danger">' . $value . '</span>';
                } else {
                    return '<span class="badge badge-secondary">' . $value . '</span>';
                }
            }),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),

            AssociationField::new('shopSubCategory', 'Catégorie')
                ->setFormTypeOptions([
                    'block_name' => 'custom_shop_category',
                ])
                ->onlyOnForms(),

            AssociationField::new('shopSubCategory', 'Catégorie')
                ->setTemplatePath('admin/_shopCategory_index.html.twig')
                ->onlyOnIndex(),

            CollectionField::new('imageProducts', 'Images')
                ->setEntryType(ImageProductType::class)
                // ->setFormTypeOptions([
                //     'block_name' => 'custom_image_product',
                // ])
                ->hideOnIndex(),

        ];
    }
}
