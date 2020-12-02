<?php

namespace App\Controller\Admin;

use App\Entity\SpeedTest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SpeedTestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpeedTest::class;
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
