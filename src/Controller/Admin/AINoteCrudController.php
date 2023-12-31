<?php

namespace App\Controller\Admin;

use App\Entity\AINote;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AINoteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AINote::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('note'),
            TextEditorField::new('description'),
        ];
    }
}
