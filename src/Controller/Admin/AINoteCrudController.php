<?php

namespace App\Controller\Admin;

use App\Entity\AINote;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AINoteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AINote::class;
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
