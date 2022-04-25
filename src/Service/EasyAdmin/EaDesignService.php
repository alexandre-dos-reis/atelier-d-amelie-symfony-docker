<?php

namespace App\Service\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class EaDesignService
{
    public function addIconsToActions(Actions $actions): Actions
    {
        $this->setIconToSingleAction($actions, Action::EDIT, 'fa fa-pencil');
        $this->setIconToSingleAction($actions, Action::DELETE, 'fa fa-trash');

        return $actions;
    }

    private function setIconToSingleAction(Actions $actions, string $action, string $fontAwesomeIcon): Actions
    {
        return $actions->update(Crud::PAGE_INDEX, $action, function (Action $action) use ($fontAwesomeIcon){
            return $action->setIcon($fontAwesomeIcon);
        });
    }
}
