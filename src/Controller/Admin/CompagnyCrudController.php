<?php

namespace App\Controller\Admin;

use App\Entity\Compagny;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CompagnyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Compagny::class;
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
