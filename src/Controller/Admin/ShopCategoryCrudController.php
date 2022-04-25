<?php

namespace App\Controller\Admin;

use App\Entity\ShopCategory;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ShopCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShopCategory::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return (new EaDesignService())->addIconsToActions($actions);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une catégorie')
            ->setEntityLabelInPlural('Liste des catégories')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            SlugField::new('slug', 'Slug')->setTargetFieldName('name')->hideOnIndex(),
            NumberField::new('disposition', 'Disposition')
        ];
    }
}
