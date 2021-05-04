<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use phpDocumentor\Reflection\Types\Boolean;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextEditorField::new('description')->hideOnIndex(),
            TextEditorField::new('moreInformations'),
            MoneyField::new('price')->setCurrency('USD'),
            IntergerField::new('quantity'),
            TextField::new('tags'),
            BooleanField::new('isBestSeller', 'Best Seller'),
            BooleanField::new('isNewArrival', 'New Arrival'),
            BooleanField::new('isFeatured', 'Featured'),
            BooleanField::new('isSpecialOffer', 'Special Offer'),
            AssociationField::new('category'),
            ImageField::new('image')->setBasePath('/asset/uploads/categories/')
                ->setUploadDir('../../../../../publics/assets/uploads/categories/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }
}
