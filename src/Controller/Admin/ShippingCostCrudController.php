<?php

namespace App\Controller\Admin;

use App\Entity\ShippingCost;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ShippingCostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShippingCost::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un palier')
            ->setEntityLabelInPlural('Liste des frais de port')
            ->setSearchFields(null);
    }

    public function configureActions(Actions $actions): Actions
    {
        return (new EaDesignService())->addIconsToActions($actions);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            MoneyField::new('max', 'Paliers')->setCurrency('EUR'),
            MoneyField::new('weightCost', 'Frais d\'affranchissement')->setCurrency('EUR'),
            MoneyField::new('insurance', 'Frais d\'assurance')->setCurrency('EUR'),
        ];
    }

    // private function hideZeros($cost): string
    // {
    //     $float = (float)substr($cost, 0);
    //     dd($float);
    //     return number_format($float, $this->hasDecimal($cost) ? 2 : 0, ',', ' ') . ' €';
    // }

    // private function hasDecimal(float $value): bool
    // {
    //     return is_numeric($value) && floor($value) != $value;
    // }
}
