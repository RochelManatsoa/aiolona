<?php

namespace App\Controller\Admin;

use App\Entity\Lang;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LangCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lang::class;
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
