<?php

namespace App\Controller\Admin;

use App\Entity\Cryptocurrencies;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CryptocurrenciesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cryptocurrencies::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('fullname'),
            TextEditorField::new('description'),
            UrlField::new('website'),
            UrlField::new('source_code'),
            UrlField::new('whitepaper'),
        ];
    }
    
}
