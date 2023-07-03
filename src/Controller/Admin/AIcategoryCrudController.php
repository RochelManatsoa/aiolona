<?php

namespace App\Controller\Admin;

use App\Entity\AIcategory;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AIcategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AIcategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextEditorField::new('description')->onlyOnForms(),
            SlugField::new('slug')->setTargetFieldName('name')
        ];
    }
}
