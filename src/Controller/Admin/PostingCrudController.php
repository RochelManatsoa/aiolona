<?php

namespace App\Controller\Admin;

use App\Entity\Posting;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PostingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Posting::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            NumberField::new('number')->onlyOnForms(),
            BooleanField::new('valid'),
            TextareaField::new('desctiption')->onlyOnForms(),
            DateTimeField::new('createdAt')->onlyOnForms(),
            DateTimeField::new('updatedAt')->onlyOnForms(),
            DateTimeField::new('startDate')->onlyOnForms(),
            AssociationField::new('compagny'),
            AssociationField::new('typePosting'),
        ];
    }
}
