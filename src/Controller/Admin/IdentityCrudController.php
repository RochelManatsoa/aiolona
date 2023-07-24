<?php

namespace App\Controller\Admin;

use App\Entity\Identity;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class IdentityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Identity::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('username')->onlyOnForms(),
            MoneyField::new('tarif')->setCurrency('EUR')->onlyOnForms(),
            TextEditorField::new('bio')->onlyOnForms(),
            AssociationField::new('account'),
            AssociationField::new('sectors')->onlyOnForms(),
            TextField::new('file')->setFormType(VichImageType::class)->onlyOnForms(),
            CollectionField::new('aIcores')->onlyOnIndex()
        ];
    }
}
