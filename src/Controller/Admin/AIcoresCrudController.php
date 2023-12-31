<?php

namespace App\Controller\Admin;

use App\Entity\AIcores;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AIcoresCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AIcores::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('type'),
            TextEditorField::new('url'),
            TextEditorField::new('description'),
            CollectionField::new('aIcategories'),
        ];
    }
}
