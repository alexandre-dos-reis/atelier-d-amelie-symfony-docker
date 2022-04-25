<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ImageProduct;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImageProductCrudController extends AbstractCrudController
{
    private string $productsFolder;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct($productsFolder, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->productsFolder = $productsFolder;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return ImageProduct::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une image')
            ->setEntityLabelInPlural('Liste des images de produits')
            ->setDateFormat('dd MMMM yyyy')
            // ->setFormThemes(['@VichUploader/Form/fields.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    // public function configureFilters(Filters $filters): Filters
    // {
    //     return $filters->add(TextFilter::new('product', 'produit'));
    // }

    public function configureActions(Actions $actions): Actions
    {
        $actions = (new EaDesignService())->addIconsToActions($actions);

        $imageEditorAction = Action::new('editArtworkImage', "Modifier l'image", 'fa fa-picture-o')
            ->linkToRoute('imageEditor', fn (ImageProduct $entity) => [
                'entityName' => 'imageProduct',
                'id' => $entity->getId()
            ]);

        $actions
            ->add(Crud::PAGE_INDEX, $imageEditorAction)
            ->disable(Action::EDIT, Action::NEW);

        return $actions->reorder(Crud::PAGE_INDEX, [
            Action::EDIT, 'editArtworkImage', Action::DELETE
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image', 'Image')
                ->setBasePath($this->productsFolder)
                ->onlyOnIndex(),

            TextField::new('imageWatermarked', "Filigrane")
                ->formatValue(function ($value) {
                    if (is_null($value)) {
                        return '<span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i></span>';
                    } else {
                        return '<span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i></span>';
                    }
                })->onlyOnIndex(),

            TextField::new('product', 'Produit')->formatValue(function ($value, $entity) {
                $colors = ['primary', 'success', 'danger', 'warning', 'info', 'dark'];

                $url = $this->adminUrlGenerator
                    ->setController(ProductCrudController::class)
                    ->setAction('edit')
                    ->setEntityId($entity->getProduct()->getId())
                    ->set('menuIndex', '6')
                    ->set('sort', [
                        'updatedAt' => 'DESC'
                    ])
                    ->generateUrl();

                $entityId = $entity->getProduct()->getId();
                $colorClass = $colors[$entityId % count($colors)];

                return '<a class="link-' . $colorClass . '" href="' . $url . '" >' . $value . '</a>';
            })->onlyOnIndex(),

            NumberField::new('disposition'),

            TextField::new('imageOriginalFile', 'Image')
                ->setFormType(VichImageType::class)
                ->setFormTypeOptions([
                    'allow_delete' => false,
                ])
                ->hideOnIndex(),
        ];
    }
}
