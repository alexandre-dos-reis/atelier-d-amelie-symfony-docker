<?php

namespace App\Controller\Admin;

use App\Entity\AppSettings;
use App\Repository\AppSettingsRepository;
use App\Service\EasyAdmin\EaDesignService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AppSettingsCrudController extends AbstractCrudController
{
    public $appSettingsRepo;

    public static function getEntityFqcn(): string
    {
        return AppSettings::class;
    }

    public function __construct(AppSettingsRepository $appSettingsRepo)
    {
        $this->appSettingsRepo = $appSettingsRepo;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = (new EaDesignService())->addIconsToActions($actions);

        if (empty($this->appSettingsRepo->findOneBy([]))) {
            return $actions->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
        } else {
            return $actions->disable(Action::DELETE, Action::NEW);
        }
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('la configuration')
            ->setEntityLabelInPlural("Configuration")
            ->setSearchFields(null);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('cgv', 'Conditions générales de vente'),
        ];
    }
}
