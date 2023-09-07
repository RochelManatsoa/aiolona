<?php

namespace App\Controller\Admin;

use App\Entity\TechnicalSkill;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TechnicalSkillCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TechnicalSkill::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('type'),
            TextField::new('url'),
            TextField::new('image'),
            TextEditorField::new('description'),
        ];
    }
}
