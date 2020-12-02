<?php

namespace App\Controller\Admin;

use App\Entity\ServiceProvider;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServiceProviderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ServiceProvider::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
