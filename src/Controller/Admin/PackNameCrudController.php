<?php

namespace App\Controller\Admin;

use App\Entity\PackName;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PackNameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PackName::class;
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
