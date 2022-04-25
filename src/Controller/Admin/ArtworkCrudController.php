<?php

namespace App\Controller\Admin;

use App\Entity\Artwork;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArtworkCrudController extends AbstractCrudController
{
    private string $artworksFolder;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(string $artworksFolder, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->artworksFolder = $artworksFolder;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Artwork::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = (new EaDesignService())->addIconsToActions($actions);

        $imageEditorAction = Action::new('editArtworkImage', "Modifier l'image", 'fa fa-picture-o')
            ->linkToRoute('imageEditor', fn (Artwork $entity) => [
                'entityName' => 'artwork',
                'id' => $entity->getId()
            ]);

        $actions
            ->add(Crud::PAGE_INDEX, $imageEditorAction)
            ->add(Crud::PAGE_EDIT, $imageEditorAction);

        return $actions->reorder(Crud::PAGE_INDEX, [
            Action::EDIT, 'editArtworkImage', Action::DELETE
        ]);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une oeuvre')
            ->setEntityLabelInPlural('Liste des oeuvres')
            ->setDateFormat('dd MMMM yyyy')
            // ->setFormThemes(['@VichUploader/Form/fields.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('publishedAt', 'Date de publication'))
            ->add(DateTimeFilter::new('createdAt', 'Date de création'))
            ->add(TextFilter::new('name', 'Titre'))
            ->add(TextFilter::new('description', 'Description'))
            ->add(EntityFilter::new('category', 'Catégories'))
            ->add(BooleanFilter::new('showInGallery', 'Affichées dans la galerie'))
            ->add(BooleanFilter::new('showInPortfolio', 'Affichées dans le portfolio'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('publishedAt', 'Date de publication')
                ->onlyOnIndex(),
            BooleanField::new('showInGallery', 'Publier ?'),
            DateField::new('createdAt', 'Date de création')
                ->hideOnIndex(),
            ImageField::new('image', 'Image')
                ->setBasePath($this->artworksFolder)
                ->onlyOnIndex(),
            TextField::new('imageWatermarked', "Filigrane")
                ->formatValue(function ($value) {
                    if (is_null($value)) {
                        return '<span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i></span>';
                    } else {
                        return '<span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i></span>';
                    }
                })->onlyOnIndex(),
            TextField::new('name', 'Titre')->formatValue(function ($value, $entity) {
                $href = $this->adminUrlGenerator
                    ->setAction('edit')
                    ->setEntityId($entity->getId());
                return '<a href="' . $href . '">' . $value . '</a>';
            }),
            TextareaField::new('description')->hideOnIndex(),
            AssociationField::new('category', 'Catégorie')
                // ->setTemplatePath('admin/_category_index.html.twig')
                ->formatValue(function ($value, $entity) {
                    $str = $entity->getCategory()[0];
                    for ($i = 1; $i < $entity->getCategory()->count(); $i++) {
                        $str = $str . ",<br>" . $entity->getCategory()[$i];
                    }
                    return $str;
                }),
            TextField::new('imageOriginalFile', 'Image')
                ->setFormType(VichImageType::class)
                ->hideOnIndex()
                ->setFormTypeOptions([
                    'allow_delete' => false,
                ]),

            // BooleanField::new('showInPortfolio', 'Publier dans le portfolio ?')
            //     ->hideOnIndex(),
        ];
    }
}
