<?php

namespace App\Controller\Admin;

use App\Entity\HonoraryPosting;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HonoraryPostingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HonoraryPosting::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name')
        ];
    }
}
