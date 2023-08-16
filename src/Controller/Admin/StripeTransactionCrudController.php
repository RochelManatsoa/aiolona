<?php

namespace App\Controller\Admin;

use App\Entity\StripeTransaction;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class StripeTransactionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StripeTransaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('commande.identity.compagny.name', 'Company')->onlyOnIndex(),
            MoneyField::new('amount')->setCurrency('EUR'),
            TextField::new('intentId'),
            TextField::new('customerId'),
            TextField::new('paymentMethod'),
            TextField::new('status'),
            TextEditorField::new('description')->onlyOnForms(),
        ];
    }
}
