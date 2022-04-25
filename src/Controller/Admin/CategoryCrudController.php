<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
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
            ->setDateFormat('dd MMMM yyyy')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('showInGallery', 'Publier ?'),
            TextField::new('name', 'Nom'),
            TextareaField::new('description', 'Description'),
            IntegerField::new('disposition', 'Disposition')
        ];
    }
}
