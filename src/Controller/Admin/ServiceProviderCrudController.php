<?php

namespace App\Controller\Admin;

use App\Entity\ServiceProvider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServiceProviderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServiceProvider::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, '<i class="fas fa-wifi"></i> IS Providers');
    }
}
