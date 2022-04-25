<?php

namespace App\Controller\Admin;

use App\Entity\Purchase;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PurchaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Purchase::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = (new EaDesignService())->addIconsToActions($actions);

        $downloadPdf = Action::new('downloadPdf', 'Télécharger la facture', 'fa fa-download')
            ->linkToRoute('pdf_invoice_download', fn (Purchase $entity) => ['uuid' => $entity->getUuid()])
            ->displayAsLink()
            ->displayIf(static function (Purchase $purchase) {
                return $purchase->getStatus() != Purchase::PAIEMENT_EN_ATTENTE;
            });

        $showPdf = Action::new('showPdf', 'Afficher la facture', 'fa fa-eye')
            ->linkToRoute('pdf_invoice_show', fn (Purchase $entity) => ['uuid' => $entity->getUuid()])
            ->displayAsLink()
            ->setHtmlAttributes(['target' => '_blank'])
            ->displayIf(static function (Purchase $purchase) {
                return $purchase->getStatus() != Purchase::PAIEMENT_EN_ATTENTE;
            });


        $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, $downloadPdf)
            ->add(Crud::PAGE_EDIT, $downloadPdf)
            ->add(Crud::PAGE_INDEX, $showPdf)
            ->add(Crud::PAGE_EDIT, $showPdf);

        return $actions->reorder(Crud::PAGE_INDEX, [
            Action::EDIT, 'downloadPdf', 'showPdf', Action::DELETE
        ]);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une commande')
            ->setEntityLabelInPlural('Liste des commandes')
            ->setDateFormat('dd MMMM yyyy')
            ->setFormThemes([
                'admin/_purchaseItem.html.twig',
                'admin/_purchaseAddresses.html.twig',
                '@EasyAdmin/crud/form_theme.html.twig'
            ]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(DateTimeFilter::new('purchasedAt', 'Date de commande'))
            ->add(TextFilter::new('uuid', 'N° de commande'))
            ->add(TextFilter::new('stripeId', 'Id Stripe'))
            ->add(ChoiceFilter::new('status', 'Statut')->setChoices(Purchase::getEasyAdminStatus()))
            ->add(TextFilter::new('trackingNumber', 'N° de colis'))
            // ->add('email')
            ->add(NumericFilter::new('total', 'Total produits'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('purchasedAt', 'Date de commande')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('uuid', 'N° de commande')->setFormTypeOption('disabled', 'disabled'),
            TextField::new('stripeId', 'Id Stripe')->setFormTypeOption('disabled', 'disabled')->hideOnIndex(),
            ChoiceField::new('status', 'Statut')->setChoices(Purchase::STATUS_ARRAY)->formatValue(function ($value) {

                $bootstrapBadgeColor = [
                    0 => 'warning',
                    1 => 'danger',
                    2 => 'warning',
                    3 => 'success',
                    4 => 'secondary',
                    5 => 'secondary'
                ];

                return '<span class="badge badge-' . $bootstrapBadgeColor[$value] . '">' . Purchase::STATUS_ARRAY[$value] . '</span>';
            })->onlyOnIndex(),

            ChoiceField::new('status', 'Statut')->setChoices(Purchase::getEasyAdminStatus())->hideOnIndex(),
            TextField::new('trackingNumber', 'N° de colis')->hideOnIndex(),
            EmailField::new('email', 'Email')->setFormTypeOption('disabled', 'disabled'),
            AssociationField::new('purchaseAddresses', 'Adresse(s)')->setFormTypeOptions([
                'block_name' => 'custom_purchase_addresses',
            ])->hideOnIndex(),
            AssociationField::new('purchaseItems', 'Détail de la commande')
                ->setFormTypeOptions([
                    'block_name' => 'custom_purchase_items',
                ])
                ->hideOnIndex(),
            // CollectionField::new('purchaseItems', 'Détail de la ')->onlyOnForms(),
            MoneyField::new('insuranceCost', "Frais d'assurance")->setCurrency('EUR')->setFormTypeOption('disabled', 'disabled'),
            MoneyField::new('weightCost', "Frais de port")->setCurrency('EUR')->setFormTypeOption('disabled', 'disabled'),
            MoneyField::new('total', 'Total produits')->setCurrency('EUR')->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
