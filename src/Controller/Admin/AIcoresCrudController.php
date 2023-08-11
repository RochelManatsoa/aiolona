<?php

namespace App\Controller\Admin;

use App\Entity\AIcores;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class AIcoresCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AIcores::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextField::new('type'),
            TextField::new('slug'),
            TextEditorField::new('url'),
            TextEditorField::new('description'),
            CollectionField::new('aIcategories'),
        ];
    }
}
