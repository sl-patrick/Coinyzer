<?php

namespace App\Controller\Admin;

use App\Entity\Cryptocurrencies;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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
            // IdField::new('id'),
            TextField::new('name'),
            TextField::new('fullname'),
            TextField::new('logoFile')->setFormType(VichImageType::class)->onlyWhenCreating(),
            ImageField::new('logo')->setBasePath('/images/logo')->onlyOnIndex(),
            TextEditorField::new('description'),
            UrlField::new('website'),
            UrlField::new('source_code'),
            UrlField::new('whitepaper'),
            BooleanField::new('published')->setFormTypeOptionIfNotSet('label_attr.class', 'switch-custom'),
        ];
    }
    
}
